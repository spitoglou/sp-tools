<?php
/**
 * Simple, 1 file class to access POP3 mail and process all the downloaded messages.
 *
 * @copy Nick Temple, http://www.nicktemple.com/
 * @license GPL V2. See License.txt
 *
 */
class POP3Server
{
    protected $host; // NOTE: use 'ssl://' if you want to use ssl
    protected $port;
    protected $username;
    private $password;
    protected $fd;
    public $bufferSize    = 4096;
    public $quit_on_error = true;
    public $logbuffer     = '';
    public $debug         = 1;
    public function __construct($host, $port, $username, $password)
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }
    public function __destruct()
    {
        if ($this->fd)
        {
            fclose($this->fd);
            $this->fd = null;
        }
    }
    /**
     * Connect to the POP3 server
     *
     * @param int $timeout
     * @throws POP3Exception
     */
    public function connect($timeout = 30)
    {
        $errno    = 0;
        $errstr   = '';
        $this->fd = fsockopen($this->host, $this->port, $errno, $errstr, $timeout);
        if ($errno)
        {
            throw new POP3Exception($errstr, $errno);
        }
        stream_set_blocking($this->fd, true);
        $buffer = '';
        if (!$this->readln($buffer))
        {
            $this->cmdQuit();
            throw new POP3Exception($buffer);
        }
        // Login
        $this->cmd("USER {$this->username}");
        $this->cmd("PASS {$this->password}");
    }
    /**
     * Called for every line sent or received.
     * Override in child classes to implement tracing.
     *
     * @param $msg
     */
    protected function writeLog($msg)
    {
        if ($this->debug)
        {
            $this->logbuffer .= "$msg\n";
        }

    }
    /**
     * Read a status line, expected +OK or -ERR
     * @param $buffer
     * @return bool|null
     */
    private function readln(&$buffer)
    {
        $buffer = fgets($this->fd, $this->bufferSize);
        $this->writelog($buffer);
        $status = substr($buffer, 0, 3);
        if ($status == '-ER')
        {
            return false;
        }

        if ($status == '+OK')
        {
            return true;
        }

        return null;
    }
    /**
     * write a command line
     * @param $msg
     */
    private function writeln($msg)
    {
        $this->writelog($msg);
        fwrite($this->fd, "$msg\r\n");
    }
    /**
     * Read a series of lines, terminated by a period.
     * @return string
     */
    private function read()
    {
        $buffer = '';
        while (!feof($this->fd))
        {
            $line = fgets($this->fd, $this->bufferSize);
            if (chop($line) == '.')
            {
                break;
            }

            $buffer .= $line;
        }
        $this->writeLog($buffer);
        return $buffer;
    }
    /**
     * Command request / response logic.
     * send request, read response, check for -ERR or +OK
     *
     * @param $cmd
     * @return string
     * @throws POP3Exception
     */
    protected function cmd($cmd)
    {
        $buffer = '';
        $this->writeln($cmd);
        $result = $this->readln($buffer);
        if ($result !== true)
        {
            if ($this->quit_on_error)
            {
                $this->cmdQuit();
            }
            #echo '<pre>';
            #echo $this->logbuffer;
            throw new POP3Exception($buffer);

        }
        return $buffer;
    }
    /*
     *  POP3 Command Implementation
     */
    public function cmdQuit()
    {
        $this->writeln('QUIT');
        fclose($this->fd);
        $this->fd = null;
    }
    public function cmdStat()
    {
        $buffer              = $this->cmd('STAT');
        list($number, $size) = explode(' ', $buffer, 2);
        return array($number, $size);
    }
    /**
     * @return mixed
     */
    public function cmdList()
    {
        $this->cmd('LIST');
        $buffer   = $this->read();
        $lines    = explode("\n", $buffer);
        $messages = array();
        foreach ($lines as $line)
        {
            if ($line == '')
            {
                continue;
            }
            list($id, $size) = explode(' ', $line, 2);
            $messages[$id]   = $size;
        }
        unset($messages['']);
        return $messages;
    }
    /**
     * @param $id
     * @param $auto_delete
     * @return mixed
     */
    public function cmdRetr($id, $auto_delete = false)
    {
        $this->cmd("RETR $id");
        $message = $this->read();
        if ($auto_delete)
        {
            $this->cmdDele($id);
        }
        return $message;
    }
    /**
     * @param $id
     */
    public function cmdDele($id)
    {
        #$this->cmd("DELE $id");
    }
    public function cmdRset()
    {
        $this->cmd("RSET");
    }
    /**
     * High level logic to read all the messages off the server,
     * process them, and return the message list.
     *
     * @param $class - the name of a class that takes a ($id, $message) in the constructor, with optional process() method
     * @param bool $delete - automatically delete the message after retrieval. Defaults to true.
     * @return array - a list of $class objects, each containing a message from the server.
     * @throws POP3Exception
     */
    /**
     * @return mixed
     */
    public function processMessages($class = 'POP3Message', $delete = true)
    {
        $this->connect();
        if (!class_exists($class))
        {
            throw new POP3Exception("Class $class does not exist.");
        }
        $messages = array();
        $this->connect();
        $messageList = $this->cmdList();
        foreach ($messageList as $id => $size)
        {
            $message    = $this->cmdRetr($id, $delete);
            $messages[] = new $class($id, $message);
        }
        $this->cmdQuit();
        // Cycle through each messages, calling their process() method if it exists.
        foreach ($messages as $message)
        {
            if (is_callable(array($message, 'process')))
            {
                $message->process();
            }
        }
        return $messages;
    }
}
class POP3Exception extends Exception
{
}
class POP3Message
{
    public $id      = '';
    public $message = '';
    /**
     * Create the message. Could be extended to parse.
     * Time consuming tasks should not be done here, as the server connection is still open.
     *
     * @param $id
     * @param $message
     */
    public function __construct($id, $message)
    {
        $this->id      = $id;
        $this->message = $message;
    }
    /**
     * Called after the connection to the server is closed
     * by default, does nothing. Here you can parse the message,
     * store it in a database, etc.
     */
    public function process()
    {
    }
    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->message;
    }
}

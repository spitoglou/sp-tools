<?php
/*
 * Process messages from a gmail inbox.
 * Note: you need to add your full gmail address as your username, and your gmail password.
 * POP3 access must also be enabled in your account.
 * See: http://www.ghacks.net/2009/06/19/gmail-pop3-configuration/
 */
include 'lib/POP3Server.php';
include 'lib/mime_parser.php';
include 'lib/rfc822_addresses.php';
define('POP_USERNAME', 'hostmaster@mantis.cssa.tk');
define('POP_PASSWORD', 'potemkin');

#initialization
require_once "lib/php-console-master/src/PhpConsole/__autoload.php";

$handler = PhpConsole\Handler::getInstance();
$handler->getConnector()->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
$handler->getConnector()->getDebugDispatcher()->detectTraceAndSource = true;

$handler->start();
$handler->debug('Started');
/*die();*/
/**
 * Class MyPOP3Server
 *
 * Optional: Extend the class in order to override the logging capability
 *
 */
class MyPOP3Server extends POP3Server
{
    public function writeLog($msg)
    {
        print "$msg\n";
    }
}
/**
 * Class MyMessage
 *
 * Optional: Process the mail message from the server after the connection is closed.
 */
class MyMessage extends POP3Message
{
    public function process()
    {
        global $handler;
        echo '<pre>';
        //print "== Message==\n{$this->message}\n==\n";
        $handler->debug($this->message, 'Raw Message');
        $mime = new mime_parser_class;

        /*
         * Set to 0 for parsing a single message file
         * Set to 1 for parsing multiple messages in a single file in the mbox format
         */
        $mime->mbox = 0;

        /*
         * Set to 0 for not decoding the message bodies
         */
        $mime->decode_bodies = 1;

        /*
         * Set to 0 to make syntax errors make the decoding fail
         */
        $mime->ignore_syntax_errors = 1;

        /*
         * Set to 0 to avoid keeping track of the lines of the message data
         */
        $mime->track_lines = 1;

        /*
         * Set to 1 to make message parts be saved with original file names
         * when the SaveBody parameter is used.
         */
        $mime->use_part_file_names = 0;

        /*
         * Set this variable with entries that define MIME types not yet
         * recognized by the Analyze class function.
         */
        $mime->custom_mime_types = array(
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => array(
                'Type'        => 'ms-word',
                'Description' => 'Word processing document in Microsoft Office OpenXML format',
            ),
        );

        $parameters = array(
            //'File'     => $message_file,

            /* Read a message from a string instead of a file */
            'Data' => $this->message,

                           /* Save the message body parts to a directory */
                           /* 'SaveBody'=>'/tmp', */

                           /* Do not retrieve or save message body parts */
                           //'SkipBody' => 1,
        );
        if (!$mime->Decode($parameters, $decoded))
        {
            echo 'MIME message decoding error: '.$mime->error.' at position '.$mime->error_position;
            if ($mime->track_lines
                && $mime->GetPositionLine($mime->error_position, $line, $column))
            {
                echo ' line '.$line.' column '.$column;
            }

            echo "\n";
        }
        else
        {
            echo 'MIME message decoding successful.'."\n";
            echo (count($decoded) == 1 ? '1 message was found.' : count($decoded).' messages were found.'), "\n";
            for ($message = 0; $message < count($decoded); $message++)
            {
                echo 'Message ', ($message + 1), ':', "\n";
                #var_dump($decoded[$message]);
                print_r($decoded[$message]);
                //$handler->debug($decoded[$message], 'Decoded Message');
                if ($mime->decode_bodies)
                {
                    if ($mime->Analyze($decoded[$message], $results))
                    {
                        #var_dump($results);
                        print_r($results);
                        //$handler->debug($results);
                    }
                    else
                    {
                        echo 'MIME message analyse error: '.$mime->error."\n";
                    }

                }
            }
            for ($warning = 0, Reset($mime->warnings); $warning < count($mime->warnings);Next($mime->warnings), $warning++)
            {
                $w = Key($mime->warnings);
                echo 'Warning: ', $mime->warnings[$w], ' at position ', $w;
                if ($mime->track_lines
                    && $mime->GetPositionLine($w, $line, $column))
                {
                    echo ' line '.$line.' column '.$column;
                }

                echo "\n";
            }
        }
    }
}
// Minimilistic Example
try {
    $server = new POP3Server('ssl://mantis.cssa.tk', 995, POP_USERNAME, POP_PASSWORD);
    /*
     * The next line:
     * - connects to the server
     * - downloads all the messages, creating a new Message object, MyMessage for each
     * - closes the connection, which deletes the messages if the 2nd param is true
     * - runs the process() function on each message, if it exists.
     */
    $messages = $server->processMessages('MyMessage', false);
    $handler->debug($messages);
    $handler->debug($server->logbuffer, 'LogBuffer');

}
catch (Exception $e)
{
    // Problem!
    $handler->debug($server->logbuffer);
    $handler->handleException($e);
    #print_r($e);
}

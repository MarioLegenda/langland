<?php

namespace ArmorBundle\Email;

use Symfony\Component\Yaml\Yaml;

class Email
{
    /**
     * @var mixed $mailer
     */
    private $mailer;
    /**
     * @var Config $config
     */
    private $config;
    /**
     * @var mixed $message
     */
    private $templating;
    /**
     * Email constructor.
     * @param $mailer
     * @param $templating
     * @param string $emailConfig
     */
    public function __construct($mailer, $templating, string $emailConfig)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->config = new Config(Yaml::parse(file_get_contents($emailConfig)));
    }
    /**
     * @param $name
     * @param string|null $to
     * @param array $templateVars
     */
    public function send($name, string $to = null, array $templateVars = array())
    {
        if ($name instanceof \Closure) {
            $name->__invoke($this->mailer, $this->config);

            return;
        }

        if (!is_string($name)) {
            throw new \RuntimeException(
                'Invalid email parameter. You have to provide a name of the configurated email'
            );
        }

        if (!is_string($to)) {
            throw new \RuntimeException(
                'Invalid \'to\' mail. Sending emails requires a receiver'
            );
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($this->config->get($name, 'subject'))
            ->setFrom($this->config->get($name, 'from'))
            ->setTo($to);

        $message->setBody(
                $this->templating->render(
                    $this->config->get($name, 'template'),
                    $templateVars
                ),
                'text/html'
            );

        return $this->mailer->send($message);
    }
}
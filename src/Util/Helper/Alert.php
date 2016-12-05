<?php

/**
 * UTIL - Classes úteis ao UACL
 * @link      https://bitbucket.org/jotjunior/util para o repositório da aplicação
 * @copyright Copyright (c) 2014 Jot! (http://jot.com.br)
 * @author    João G. Zanon Jr. <jot@jot.com.br>
 */

namespace Util\Helper;

use Zend\View\Helper\AbstractHelper;
use Util\StdLib\Util;

/**
 * Cria uma mensagem de alerta
 *
 * @author jot
 */
class Alert extends AbstractHelper
{

    protected $templates = array(
        'mac-admin' => "noty({text: \"%s\",layout:\"topRight\",type:\"%s\",timeout:15000})",
        'core-admin' => "",
        'ace-responsive' => "",
    );

    public function __invoke($template, $message, $class = 'information')
    {
        $inlineScript = $this->getView()->plugin('inlineScript');
        $inlineScript->captureStart();
        echo sprintf($this->templates[$template], $this->prepareMessage($message), $class);
        $inlineScript->captureEnd();
    }

    public function prepareMessage($message)
    {
        if (is_array($message)) {
            return str_replace("\n", "", Util::arrayToString($message));
        }

        return addslashes($message);
    }

}

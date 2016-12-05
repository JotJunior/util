<?php

/**
 * UTIL - Classes úteis ao UACL
 * @link      https://bitbucket.org/jotjunior/util para o repositório da aplicação
 * @copyright Copyright (c) 2014 Jot! (http://jot.com.br)
 * @author    João G. Zanon Jr. <jot@jot.com.br>
 */

namespace Util\Helper;

use Zend\View\Helper\AbstractHelper;
use Util\StdLib\Browser;

/**
 * Retorna dados do browser do cliente
 *
 * @author jot
 */
class Browser extends AbstractHelper
{

    public function __invoke()
    {
        return new Browser;
    }

}

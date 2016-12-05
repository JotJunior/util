<?php

/**
 * UTIL - Classes úteis ao UACL
 * @link      https://bitbucket.org/jotjunior/util para o repositório da aplicação
 * @copyright Copyright (c) 2014 Jot! (http://jot.com.br)
 * @author    João G. Zanon Jr. <jot@jot.com.br>
 */

namespace Util\StdLib;

class Browser
{

    /**
     * @var string 
     */
    protected $userAgent;

    /**
     *
     * @var string Nome do browser
     */
    protected $browser;

    /**
     *
     * @var string Nome da Plataforma/Sistema Operacional
     */
    protected $osPlatform;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];

        /**
         * Seta falores default para browser e OS
         */
        $this->browser = 'Browser Desconhecido';
        $this->osPlatform = 'Sistema Operacional desconhecido';

        /**
         * Detectando o browser do usuário
         */
        $this->detectBrowser();
    }

    protected function detectBrowser()
    {

        /**
         * Verifica se o userAgent é Windows
         */
        if (preg_match('/windows|win32/i', $this->userAgent)) {

            $this->osPlatform = 'Windows';

            if (preg_match('/windows nt 6.2/i', $this->userAgent)) {
                /**
                 * WINDOWS 8
                 */
                $this->osPlatform .= " 8";
            } else if (preg_match('/windows nt 6.1/i', $this->userAgent)) {
                /**
                 * WINDOWS 7
                 */
                $this->osPlatform .= " 7";
            } else if (preg_match('/windows nt 6.0/i', $this->userAgent)) {
                /**
                 * WINDOWS VISTA
                 */
                $this->osPlatform .= " Vista";
            } else if (preg_match('/windows nt 5.2/i', $this->userAgent)) {
                /**
                 * WINDOWS SERVER 2003/XP x64
                 */
                $this->osPlatform .= " Server 2003/XP x64";
            } else if (preg_match('/windows nt 5.1/i', $this->userAgent) || preg_match('/windows xp/i', $this->userAgent)) {
                /**
                 * WINDOWS XP
                 */
                $this->osPlatform .= " XP";
            } else if (preg_match('/windows nt 5.0/i', $this->userAgent)) {
                /**
                 * WINDOWS 2000
                 */
                $this->osPlatform .= " 2000";
            } else if (preg_match('/windows me/i', $this->userAgent)) {
                /**
                 * WINDOWS ME (credo!)
                 */
                $this->osPlatform .= " ME";
            } else if (preg_match('/win98/i', $this->userAgent)) {
                /**
                 * WINDOWS 98
                 */
                $this->osPlatform .= " 98";
            } else if (preg_match('/win95/i', $this->userAgent)) {
                /**
                 * WINDOWS 95 (Jura que tem gente que usa isso ainda?)
                 */
                $this->osPlatform .= " 95";
            } else if (preg_match('/win16/i', $this->userAgent)) {
                /**
                 * WINDOWS 3.11 (Para quem acessa de um museu, provavelmente)
                 */
                $this->osPlatform .= " 3.11";
            }
            /**
             * Teste agora com Macintosh
             */
        } else if (preg_match('/macintosh|mac os x/i', $this->userAgent)) {

            $this->osPlatform = 'Mac';

            if (preg_match('/macintosh/i', $this->userAgent)) {
                /**
                 * MAC OS X
                 */
                $this->osPlatform .= " OS X";
            } else if (preg_match('/mac_powerpc/i', $this->userAgent)) {
                /**
                 * MAC OS 9
                 */
                $this->osPlatform .= " OS 9";
            }
        } else if (preg_match('/linux/i', $this->userAgent)) {
            /**
             * MAC Linux (Genérico. Tá pedindo demais saber a distro também, hein?)
             */
            $this->osPlatform = "Linux";
        }


        /**
         * Sobrescreve a porra toda se encontrar algo aqui em baixo
         */
        if (preg_match('/iphone/i', $this->userAgent)) {
            /**
             * iOS
             */
            $this->osPlatform = "iOS";
        } else if (preg_match('/android/i', $this->userAgent)) {
            /**
             * Android
             */
            $this->osPlatform = "Android";
        } else if (preg_match('/blackberry/i', $this->userAgent)) {
            /**
             * BlackBerry
             */
            $this->osPlatform = "BlackBerry";
        } else if (preg_match('/webos/i', $this->userAgent)) {
            /**
             * Celular de pobre
             */
            $this->osPlatform = "Mobile";
        } else if (preg_match('/ipod/i', $this->userAgent)) {
            /**
             * iPod (you também!)
             */
            $this->osPlatform = "iPod";
        } else if (preg_match('/ipad/i', $this->userAgent)) {
            /**
             * iPad
             */
            $this->osPlatform = "iPad";
        }

        /**
         * Agora vamos identificar o Browser
         */
        if (preg_match('/msie/i', $this->userAgent) && !preg_match('/opera/i', $this->userAgent)) {
            /**
             * Aquele que não deve ser nomeado, digo utilizado
             */
            $this->browser = "Internet Explorer";
        } else if (preg_match('/firefox/i', $this->userAgent)) {
            /**
             * Firefox
             */
            $this->browser = "Firefox";
        } else if (preg_match('/chrome/i', $this->userAgent)) {
            /**
             * Chrome
             */
            $this->browser = "Chrome";
        } else if (preg_match('/safari/i', $this->userAgent)) {
            /**
             * Safari
             */
            $this->browser = "Safari";
        } else if (preg_match('/opera/i', $this->userAgent)) {
            /**
             * Opera
             */
            $this->browser = "Opera";
        } else if (preg_match('/netscape/i', $this->userAgent)) {
            /**
             * Direto do túnel do tempo!
             */
            $this->browser = "Netscape";
        }

        /**
         * Sobresreve o browser se encontrar um desses aí
         */
        if ($this->osPlatform == "Android" || $this->osPlatform == "BlackBerry" || $this->osPlatform == "Mobile" || $this->osPlatform == "iPod" || $this->osPlatform == "iPad") {
            if (preg_match('/mobile/i', $this->userAgent)) {
                $this->browser = "Handheld Browser";
            }
        }
    }

    public function getName()
    {
        return $this->browser;
    }

    public function getOs()
    {
        return $this->osPlatform;
    }

}

<?php

namespace Util\Validator\Brasil;

use Zend\Validator\AbstractValidator;

class Cpf extends AbstractValidator {

	const INVALID = "CPFInvalido.";

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $messageTemplates = array(
		self::INVALID => "CPF Inválido.",
	);
	
	/**
	 * Lista de CPFs que retornam cálculo verdadeiro, porém são inválidos
	 * @var array
	 */
	protected $invalidCpfs = array(
		'12345678909',
		'00000000000',
		'11111111111',
		'22222222222',
		'33333333333',
		'44444444444',
		'55555555555',
		'66666666666',
		'77777777777',
		'88888888888',
		'99999999999',
	);

	/**
	 * Returns true if and only if $value meets the validation requirements
	 *
	 * If $value fails validation, then this method returns false, and
	 * getMessages() will return an array of messages that explain why the
	 * validation failed.
	 *
	 * @param  mixed $value
	 * @return boolean
	 * @throws Exception\RuntimeException If validation of $value is impossible
	 */
	public function isValid($value) {
		$cpf = $this->trimCPF($value);

		if (!$this->applyingCpfRules($cpf)) {
			$this->error(self::INVALID);
			return false;
		}

		return true;
	}

	/**
	 * @param $cpf
	 * @return string
	 */
	private function trimCPF($cpf) {
		$cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
		return $cpf;
	}

	/**
	 * @param $cpf
	 * @return bool
	 */
	private function applyingCpfRules($cpf) {

		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				return false;
			}
		}
		return true;
	}

}

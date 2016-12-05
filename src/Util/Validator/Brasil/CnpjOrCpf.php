<?php

namespace Util\Validator\Brasil;

use Zend\Validator\AbstractValidator;

class CnpjOrCpf extends AbstractValidator {

	const INVALID_CPF = "CPFInvalido";
	const INVALID_CNPJ = "CNPJInvalido";
	const INVALID_DOCUMENT = "DOCUMENTOInvalido";

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $messageTemplates = array(
		self::INVALID_CPF => "CPF inválido.",
		self::INVALID_CNPJ => "CNPJ inválido.",
		self::INVALID_DOCUMENT => "CPF ou CNPJ inválido.",
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
		$cpfValidator = new \Util\Validator\Brasil\Cpf;
		if ($cpfValidator->isValid($value)) {
			return true;
		}

		$cnpjValidator = new \Util\Validator\Brasil\Cnpj;
		if ($cnpjValidator->isValid($value)) {
			return true;
		}

		if (preg_replace("/[^0-9]/", "", $value) == 11) {
			$this->error(self::INVALID_CPF);
		} elseif (preg_replace("/[^0-9]/", "", $value) == 14) {
			$this->error(self::INVALID_CNPJ);
		} else {
			$this->error(self::INVALID_DOCUMENT);
		}

		return false;
	}

}
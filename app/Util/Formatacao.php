<?php

namespace App\Util;

class Formatacao
{

	public static function prefixoSufixo($vvalor, $vprefixo = null, $vsufixo = null, $vcasas = 2)
	{

		return $vprefixo . number_format($vvalor, $vcasas, ",", ".") . $vsufixo;
	}

	public static function chave($str)
	{

		$array = array(
			"" => array("_", " "),
			"" => array(" ", ".", "+", "*", "#", "@", "%", "&", "?", "[", "]", "{", "}", "<", ">", ":", "~", "^", "|", "´", "`", "^", "~", "ª", "º", "²", "¹", "¨",  "'", '"', ".", ",", ";", "(", ")", "!", "\n", "\r", "\t", "/", "\\"),
			"a" => array("á", "à", "ã", "â", "Á", "À", "Ã", "Â"),
			"e" => array("é", "è", "ê", "É", "È", "Ê"),
			"i" => array("í", "ì", "î", "Í", "Ì", "Î"),
			"o" => array("ó", "ò", "õ", "ô", "Ó", "Ò", "Õ", "Ô"),
			"u" => array("ú", "ù", "û", "Ú", "Ù", "Û"),
			"c" => array("ç", "Ç")
		);


		foreach ($array as $vcaracter => $mcaracteres)
			foreach ($mcaracteres as $vcarc)
				$str = str_replace($vcarc, $vcaracter, $str);


		return str_replace("?", "", strtolower($str));
	}

	public static function chaveUrl($str)
	{

		$array = array(
			"+" => array(" "),
			"-" => array("_"),
			"" => array("*", "#", "@", "%", "&", "?", "[", "]", "{", "}", "<", ">", ":", "~", "^", "|", "´", "`", "^", "~", "ª", "º", "²", "¹", "¨",  "'", '"', ".", ",", ";", "(", ")", "!", "\n", "\r", "\t", "/", "\\"),
			"a" => array("á", "à", "ã", "â", "Á", "À", "Ã", "Â", "ä", "Ä"),
			"e" => array("é", "è", "ê", "É", "È", "Ê", "ë", "Ë"),
			"i" => array("í", "ì", "î", "Í", "Ì", "Î", "ï", "Ï"),
			"o" => array("ó", "ò", "õ", "ô", "Ó", "Ò", "Õ", "Ô", "ö", "Ö"),
			"u" => array("ú", "ù", "û", "Ú", "Ù", "Û", "ü", "Ü"),
			"c" => array("ç", "Ç")
		);


		foreach ($array as $vcaracter => $mcaracteres)
			foreach ($mcaracteres as $vcarc)
				$str = str_replace($vcarc, $vcaracter, $str);


		return str_replace("?", "", strtolower($str));
	}

	public static function valorReal($vvalor)
	{

		if ($vvalor == "")
			return "0.00";
		else
			return str_replace(",", ".", str_replace(".", "", $vvalor));
	}

	public static function diasPeriodo($vdata_inicio, $vdata_final)
	{

		if (strpos($vdata_inicio, " ")) {
			list($vdata_inicio, $horai) = explode(" ", $vdata_inicio);
		}

		if (strpos($vdata_final, " ")) {
			list($vdata_final, $horaf) = explode(" ", $vdata_final);
		}

		list($vano, $vmes, $vdia) = explode("-", $vdata_inicio);
		$vinicio = mktime(0, 0, 0, $vmes, $vdia, $vano);

		list($vano, $vmes, $vdia) = explode("-", $vdata_final);
		$vfim = mktime(0, 0, 0, $vmes, $vdia, $vano);

		$periodo_segundos = $vfim - $vinicio;
		$periodo_dias = $periodo_segundos / 60 / 60 / 24;

		return $periodo_dias;
	}

	public static function data($vdata, $segundos = false, $minutos = true)
	{
		$hora = "";
		if ($vdata == "" || $vdata == "00/00/0000" || $vdata == "0000-00-00" || $vdata == "00/00/0000 00:00" || $vdata == "0000-00-00 00:00")
			return "";

		if (strpos($vdata, " ")) {
			list($vdata, $hora) = explode(" ", $vdata);
		}

		if ($vdata && $hora && $minutos)
			return (strpos($vdata, "/") ? implode("-", array_reverse(explode("/", $vdata))) : implode("/", array_reverse(explode("-", $vdata)))) . " - " . ($segundos == true ? $hora : substr($hora, 0, 5));
		elseif ($vdata)
			return (strpos($vdata, "/") ? implode("-", array_reverse(explode("/", $vdata))) : implode("/", array_reverse(explode("-", $vdata))));
		else
			return "";
	}


	static function hora($hora, $showHora = true, $showMinutos = true, $showSegundos = true)
	{
		if ($hora == "" || $hora == null) {
			return null;
		}

		list($horas, $minuto, $segundos) = explode(":", $hora);
		if ($showHora == true && $showMinutos == true && $showSegundos == true) {
			return $hora;
		} elseif ($showHora == true && $showMinutos == true && $showSegundos == false) {
			return "$horas:$minuto";
		} elseif ($showHora == true && $showMinutos == false && $showSegundos == false) {
			return "$horas";
		} else {
			return $hora;
		}
	}


	public static function CortaTexto($str, $carac)
	{

		$vstring = substr($str, 0, $carac);

		if (strlen($str) > strlen($vstring))
			$vstring .= " ...";

		return $vstring;
	}


	public static function removeAcento($str)
	{
		$str = trim($str);
		$array = array(
			"" => array(" ", "*", "#", "@", "%", "&", "?", "[", "]", "{", "}", "<", ">", ":", "~", "^", "|", "´", "`", "^", "~", "ª", "º", "²", "¹", "¨",  "'", '"', ".", ",", ";", "(", ")", "!", "\n", "\r", "\t", "/", "\\"),
			"a" => array("á", "à", "ã", "â", "Á", "À", "Ã", "Â", "ä", "Ä"),
			"e" => array("é", "è", "ê", "É", "È", "Ê", "ë", "Ë"),
			"i" => array("í", "ì", "î", "Í", "Ì", "Î", "ï", "Ï"),
			"o" => array("ó", "ò", "õ", "ô", "Ó", "Ò", "Õ", "Ô", "ö", "Ö"),
			"u" => array("ú", "ù", "û", "Ú", "Ù", "Û", "ü", "Ü"),
			"c" => array("ç", "Ç")
		);


		foreach ($array as $vcaracter => $mcaracteres)
			foreach ($mcaracteres as $vcarc)
				$str = str_replace($vcarc, $vcaracter, $str);


		return str_replace(" ", "", strtolower($str));
	}

	public static  function diaSemanaTexto($dia_semana, $abreviado = true)
	{

		switch ($dia_semana) {

			case 0:
				$dia_semana_texto =  "Domingo";
				break;

			case 1:
				$dia_semana_texto =  "Segunda";
				break;

			case 2:
				$dia_semana_texto =  "Terça";
				break;

			case 3:
				$dia_semana_texto =  "Quarta";
				break;

			case 4:
				$dia_semana_texto =  "Quinta";
				break;

			case 5:
				$dia_semana_texto =  "Sexta";
				break;

			case 6:
				$dia_semana_texto =  "Sábado";
				break;

			default:
				$dia_semana_texto =  "";
		}

		if ($abreviado)
			return substr($dia_semana_texto, 0, 1);

		else
			return $dia_semana_texto;
	}



	public static  function mesTexto($mes)
	{

		switch ($mes) {

			case 1:
				$mes_texto =  "Janeiro";
				break;

			case 2:
				$mes_texto =  "Fevereiro";
				break;

			case 3:
				$mes_texto =  "Março";
				break;

			case 4:
				$mes_texto =  "Abril";
				break;

			case 5:
				$mes_texto =  "Maio";
				break;

			case 6:
				$mes_texto =  "Junho";
				break;

			case 7:
				$mes_texto =  "Julho";
				break;

			case 8:
				$mes_texto =  "Agosto";
				break;

			case 9:
				$mes_texto =  "Setembro";
				break;

			case 10:
				$mes_texto =  "Outubro";
				break;

			case 11:
				$mes_texto =  "Novembro";
				break;

			case 12:
				$mes_texto =  "Dezembro";
				break;

			default:
				$mes_texto =  "Mês Inválido";
		}

		return $mes_texto;
	}



	static function showTeste($var, $metodo = "p", $pararExecucao = true)
	{

		echo "<div style='position: absolute; top:0; left:0; background: #FFF; min-height: 100px; min-width: 100px;' >";
		echo "<span style='font-size: 20px; display:table-cell; vertical-align: middle; width: 100px; height: 100px;'>";

		if ($metodo == "v") {
			var_dump($var);
		} elseif ($metodo == "p") {

			echo "<pre>";
			print_r($var);
			echo "</pre>";
		} elseif ($metodo == "t") {
			echo $var;
		}

		echo "</span>";

		echo "</div>";

		if ($pararExecucao)
			die;
	}

	public static function array_sort($array, $on, $order = SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}
}

<?php
/**
 * Returns a translated string if one is found, with the first first character made uppercase ; Otherwise, the submitted message.
 *
 * @param $singular
 * @param $return
 * @return mixed
 * @author Nicolas Rod
 */
function ___($singular, $args = null)
{
    $translation = __($singular, $args);
    return ucfirst_special($translation);
}

/**
 * Returns a translated string if one is found, with the first first character made uppercase ; Otherwise, the submitted message.
 *
 * @param string $plugin
 * @param string $singular
 * @param boolean $return
 * @return mixed
 * @author Nicolas Rod
 */
function ___d($plugin, $singular, $args = null)
{
	$translation = __d($plugin, $singular, $args);
    return ucfirst_special($translation);
}

/**
 * Upper case the first letter of a word, even if it is a letter with an accent
 *
 * @param $word
 * @author Nicolas Rod
 */
function ucfirst_special($word)
{
	$word = ucfirst($word);
    
	/*
     * Case of special chars
     */
    $special_chars = array(
    'à' => 'À',
    'á' => 'Á',
    'â' => 'Â',
    'ä' => 'Ä',
    'ã' => 'Ã',
    
    'é' => 'É',
    'è' => 'È',
    'ê' => 'Ê',
    'ë' => 'Ë',
    'ẽ' => 'Ẽ',
    
    'ì' => 'Ì',
    'í' => 'Í',
    'î' => 'Î',
    'ï' => 'Ï',
    'ĩ' => 'Ĩ',
    
    'ò' => 'Ò',
    'ó' => 'Ó',
    'ô' => 'Ô',
    'ö' => 'Ö',
    'õ' => 'Õ',
    
    'ù' => 'Ù',
    'ú' => 'Ú',
    'û' => 'Û',
    'ü' => 'Ü',
    'ũ' => 'Ũ',
    );
    
    foreach($special_chars as $min => $maj)
    {
        if(StringTool :: start_with($word, $min))
        {
            $sub_upper_cased = substr($word, 2);
            $word = $maj . $sub_upper_cased;
            
            break;
        }
    }
    
    return $word;
}

?>
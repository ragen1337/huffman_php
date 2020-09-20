<?php
$text = $_POST['text'];

$normalized_text_array = unicode_normalizing( $text );

$alphabet = alphabet_creating($normalized_text_array);

usort($alphabet , "alphabet_sort");

$encoded_list = huffman_algorithm($alphabet);
$encoded_text = text_encrypting( $normalized_text_array , $encoded_list );

$table = table_drawing( $encoded_list );

function table_drawing( $encoded_list ){
    $counter = 0;
    $table_string = "";
    foreach ($encoded_list[0]['letters'] as $letter => $code) {
        $counter++;
        $table_string .=  "
                <tr>
                    <td>$letter</td>
                    <td>$code</td>     
                </tr>     
            ";
    }

    return ['table_string' => $table_string,
        'table_counter ' => $counter];
}

//finds exploded unicode symbols and implode them
function unicode_normalizing( $string ){
    $text_array = str_split($string);
    foreach($text_array as $key => $symbol){
        if( $symbol === "&" ){
            $i = $key;
            $flag = false;
            do{
                $i++;
                $text_array[$key] .= $text_array[$i];

                if($text_array[$i] === ";"){
                    $flag = true;
                }
                unset($text_array[$i]);
            }while(!$flag);
        }
    }

    return($text_array);
}

//makes unique alphabet from text_array and counts each letter
function alphabet_creating( $text_array ){
    $alphabet = [];
    foreach ( $text_array as $text_letter_key => $letter ){
        if( $letter == "\r" || $letter == "\n" ){
            continue;
        }

        $unique_letter_flag = 0;
        foreach( $alphabet as $key => $alphabet_letters_info ){
            foreach( $alphabet_letters_info['letters'] as $alphabet_letter => $code_word ){
                if( $letter == $alphabet_letter ){
                    $unique_letter_flag = 1;
                    $alphabet[$key]['amount']++;
                    break 2;
                }
            }
        }

        if( $unique_letter_flag ){
            continue;
        }else{
            array_push($alphabet , ['letters' => [$letter . "" => ''],
                'amount' => 1] );
        }
    }

    return $alphabet;
}

//helper sort function
function alphabet_sort( $a, $b ){
    if ( $a['amount'] === $b['amount'] ) {
        return 0;
    }
    return ($a['amount'] < $b['amount']) ? -1 : 1;
}

//return array with encoded words
function huffman_algorithm( $letter_list ){
    if( count($letter_list) === 1 ){
        foreach( $letter_list[0]['letters'] as $letter => $code_word ){
            $letter_list[0]['letters'][$letter] .= '1';
        }
        return $letter_list;
    }

    while(count($letter_list) > 1){
        $elem1 = array_shift($letter_list);
        $elem2 = array_shift($letter_list);

        foreach( $elem1['letters'] as $letter => $code_word ){
            $elem1['letters'][$letter] .= '0';
        }

        foreach( $elem2['letters'] as $letter => $code_word ){
            $elem2['letters'][$letter] .= '1';
        }

        $new_elem['amount'] = $elem1['amount'] + $elem2['amount'];

        foreach( $elem2['letters'] as $letter => $code_word ){
            $new_elem['letters'][$letter] = $code_word;
        }

        foreach( $elem1['letters'] as $letter => $code_word ){
            $new_elem['letters'][$letter] = $code_word;
        }

        array_unshift($letter_list, $new_elem);
        unset($new_elem);

        usort($letter_list, "alphabet_sort");
    }


    foreach($letter_list[0]['letters'] as $letter => $code_word){
        $letter_list[0]['letters'][$letter] = strrev($code_word);
    }

    return $letter_list;
}

//text encoding
function text_encrypting( $normalized_text_array , $letter_list ){
    $encoded_text = "";
    foreach( $normalized_text_array as $letter ){
        $encoded_text .= $letter_list[0]['letters'][$letter];
    }

    return $encoded_text;
}

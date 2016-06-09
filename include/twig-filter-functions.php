<?php
  
add_filter('timber/twig', function($twig){
    $twig->addFilter(new \Twig_SimpleFilter('randomize',function($original, $offset = 0){        
        if (!is_array($original)) {
            return $original;
        }
        if ($original instanceof \Traversable) {
            $original = iterator_to_array($original, false);
        }
        $sorted = array();
        $random = array_slice($original, $offset);
        shuffle($random);
        $sizeOf = sizeof($original);
        for ($x = 0; $x < $sizeOf; $x++) {
            if ($x < $offset) {
                $sorted[] = $original[$x];
            } else {
                $sorted[] = array_shift($random);
            }
        }
        return $sorted;        
    }));
    
    $twig->addFilter(new \Twig_SimpleFilter('sort_by_key',function(array $input, $filter, $direction = SORT_ASC){
        $output = array();
        if (!$input) {
            return $output;
        }
        foreach ($input as $key => $row) {
            $output[$key] = $row[$filter];
        }
        array_multisort($output, $direction, $input);
        return $input;        
    }));
    
    $twig->addFilter(new \Twig_SimpleFilter('ksort',function($array){
        if (is_null($array)) {
            $array = array();
        }
        ksort($array);
        return $array;       
    }));
    
    $twig->addFilter(new \Twig_SimpleFilter('regex_replace',function($subject, $pattern, $replace, $limit = -1){
        return preg_replace($pattern, $replace, $subject, $limit);
    }));        
    return $twig;   
}); 

?>
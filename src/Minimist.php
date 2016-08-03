<?php

namespace Ac;

abstract class Minimist {

  static public function parse(array $argv, array $options = []) {
    if(!isset($options['string'])) $options['string'] = [];
    if(!isset($options['boolean'])) $options['boolean'] = [];
    if(!isset($options['alias'])) $options['alias'] = [];
    if(!isset($options['default'])) $options['default'] = [];
    if(!isset($options['stopEarly'])) $options['stopEarly'] = false;
    if(!isset($options['unknown'])) $options['unknown'] = false;

    if(!is_array($options['string'])) $options['string'] = [$options['string']];
    if(!is_array($options['boolean'])) $options['boolean'] = [$options['boolean']];

    foreach($options['alias'] as $key => $aliases) {
      if(!is_array($aliases)) {
        $options['alias'][$key] = [$aliases];
      }
    }

    if($options['unknown']) {
      $known = array_merge($options['string'], $options['boolean'], $options['default']);
      foreach($options['alias'] as $key => $aliases) {
        foreach($aliases as $alias) {
          $known[] = $alias;
        }
      }
      $known = array_unique($known);
    }

    $opts = [ '_' => [] ];
    array_shift($argv);

    $len = count($argv);
    for($i=0; $i<$len; $i++) {
      $arg = $argv[$i];
      if($arg === '--') {
        $opts['_'] = array_merge($opts['_'], array_slice($argv, $i+1));
        break;
      } else if(strpos($arg, '--') === 0) { // longopt
        $value = explode('=', substr($arg, 2), 2);
        $key = array_shift($value);
        if($options['unknown'] && array_search($key, $known) === false && !call_user_func($options['unknown'], $key)) {
          continue;
        }
        if(array_search($key, $options['boolean']) !== false) {
          $opts[$key] = true;
          continue;
        } else if(!empty($value)) {
          $value = array_pop($value);
        } else {
          if(isset($argv[$i+1]) && $argv[$i+1][0] !== '-') {
            $value = $argv[$i+1];
            ++$i;
          } else if(array_search($key, $options['string']) !== false) {
            $value = '';
          } else {
            $value = true;
          }
        }
        $opts[$key] = $value;
      } else if($arg[0] === '-') { // shortopt
        $arg = substr($arg, 1);
        while(!empty($arg)) {
          $c = $arg[0];
          $arg = substr($arg, 1);
          if(strlen($arg) !== 0 && ($value = (int) $arg)) {
            $opts[$c] = $value;
            $arg = substr($arg, strlen($value));
          } else {
            $opts[$c] = true;
          }
          if($options['unknown'] && array_search($key, $known) === false && !call_user_func($options['unknown'], $key)) {
            unset($opts[$c]);
          }
        }
      } else if($options['stopEarly']) {
        $opts['_'] = array_merge($opts['_'], array_slice($argv, $i+1));
        break;
      } else {
        $opts['_'][] = $arg;
      }
    }

    foreach($options['default'] as $key => $value) {
      if(!isset($opts[$key])) {
        if(is_callable($value)) {
          $opts[$key] = call_user_func($value, $key);
        } else {
          $opts[$key] = $value;
        }
      }
    }

    foreach($options['alias'] as $key => $aliases) {
      if(isset($opts[$key])) {
        foreach($aliases as $alias) {
          if(!isset($opts[$alias])) {
            $opts[$alias] = $opts[$key];
          }
        }
      }
    }

    return $opts;
  }

}

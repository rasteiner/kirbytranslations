<?php

namespace Toastlab\Kirby\Plugins\Translations;
use \Kirby\Panel\Topbar;
use \l;
use \r;
use \c;
use \s;
use \yaml;
use \Exception;


if(class_exists('Panel')) {

  class TranslationsController extends \Kirby\Panel\Controllers\Base {

    public static function setToCode($code, $language) {
      $languagesRoot = panel()->site()->kirby()->roots()->languages();
      $file = $languagesRoot . DS . $code;
      
      $matches = glob("$file.*");

      if(count($matches) == 0) {
        //if there is no file, create it as php
        self::setToPHP("$file.php", $language);

      } else {
        $file = $matches[0];

        $info = pathinfo($file);
        
        switch (strtolower($info['extension'])) {
          case 'yml':
          case 'yaml':
            self::setToYML($file, $language);
            break;
          
          case 'php':
            self::setToPHP($file, $language);
            break;
        }
      }
    }

    public static function getFromCode($code) {
      $languagesRoot = panel()->site()->kirby()->roots()->languages();
      $file = $languagesRoot . DS . $code;

      if(file_exists("$file.php")) {
        return self::getFromPHP("$file.php");
      } elseif(file_exists("$file.yml")) {
        return self::getFromYML("$file.yml");
      } elseif(file_exists("$file.yaml")) {
        return self::getFromYML("$file.yaml");
      }

      return [];
    }

    public static function getFromPHP($file) {
      $backup = l::$data;
      l::$data = array();
      ob_start();
      include $file;
      ob_end_clean();
      $data = l::$data;
      l::$data = $backup;
      return $data;
    }

    public static function setToPHP($file, $language) {
      $content = "<?php \n\n";

      foreach ($language as $key => $value) {
        $content .= 'l::set(\'' . addcslashes($key, '\\\'') . '\', \'' . addcslashes($value, '\\\'') . '\');';
        $content .= "\n";
      }

      file_put_contents($file, $content);
    }

    public function setToYML($file, $language) {
      yaml::write($file, $language);
    }

    public static function getFromYML($file) {
      return yaml::read($file);
    }

    public function view($file, $data = array()) {
      return new TranslationsView($file, $data);
    }

    public function getTranslations() {
      $translations = array();
      $languages = [];

      foreach (c::get('languages') as $lang) {
        $code = $lang['code'];

        if($lang['default']) {
          //the default language should be first
          array_unshift($languages, $code); 
        } else {
          $languages[] = $code;
        }

        $translations[$code] = self::getFromCode($code);
      }

      //merge keys from all languages
      $keys = [];
      foreach ($translations as $code => $data) {
        foreach ($data as $key => $value) {
          $keys[$key] = 1;
        }
      }
      $keys = array_keys($keys);

      return compact('languages', 'translations', 'keys');
    }

    public function setTranslations() {
      $post = r::data("jsondata");
      $post = json_decode($post, true);

      foreach (c::get('languages') as $lang) {
        $code = $lang['code'];
        if($post[$code]) {
          self::setToCode($code, $post[$code]);
        }
      }

      panel()->kirby()->cache()->flush();
    }

    public function index() {
      if(r::is('post')) {
        $csrf = get('csrf');

        if(empty($csrf) or $csrf !== s::get('kirby_panel_csrf')) {
          try {
            panel()->user()->logout();
          } catch(Exception $e) {}
          
          panel()->redirect('login');
        } else {
          $this->setTranslations();
          return panel()->notify(':)');
        }
      }

      return $this->screen('index', new TopbarGenerator(), $this->getTranslations());
    }
  }

  class TopbarGenerator {
    public function topbar(Topbar $topbar) {
      $topbar->append(panel()->site()->url() . '/panel/translations', 'Translations');
    }
  }

  class TranslationsView extends \Kirby\Panel\View {
    public function __construct($file, $data = array()) {
      parent::__construct($file, $data);

      $this->_root = __DIR__ . DS . 'views';
    }
  }

  $panel = panel();

  $panel->routes[] = [
    'pattern' => 'translations',
    'action'  => function() {
      $ctrl = new TranslationsController();
      return $ctrl->index();
    },
    'method'  => 'GET|POST',
    'filter'  => array('auth')
  ];

}

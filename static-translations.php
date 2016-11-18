<?php

namespace Toastlab\Kirby\Plugins\Translations;
use \Kirby\Panel\Topbar;
use \l;
use \r;

if(class_exists('Panel')) {

	class TranslationsController extends \Kirby\Panel\Controllers\Base {
		public function view($file, $data = array()) {
			return new TranslationsView($file, $data);
		}

		public function getTranslations() {
			$languagesRoot = panel()->site()->kirby()->roots()->languages();
			$languages = array();
			$translations = array();

			$backup = l::$data;

			// build the keys to translate from defaultLanguage
			l::$data = array();
			$lang = site()->defaultLanguage()->code();
			ob_start();
			include $languagesRoot . DS . $lang .'.php';
			ob_end_clean();
			
			$keys = array_keys(l::$data);

			foreach (glob($languagesRoot . DS . '*.php') as $file) {
				l::$data = array();
				$lang = basename($file, '.php');
				$languages[] = $lang;

				ob_start();
				include $file;
				ob_end_clean();

				foreach ($keys as $key) {
					$translations[$key][$lang] = l::get($key, "");
				}
			}

			l::$data = $backup;

			return compact('languages', 'translations');
		}

		public function setTranslations() {
			$languagesRoot = panel()->site()->kirby()->roots()->languages() . DS;

			$post = file_get_contents('php://input');
			$post = array_map(function($item) {
				
				$splode = array_map('urldecode', explode('=', $item));

				return [
					'key' => $splode[0],
					'value' => $splode[1]
				];

			}, explode('&', $post));

			$submitted = array_filter($post, function($pair) {
				return strpos($pair['key'], 'trans__') === 0;
			});
			
			$translations = array();

			foreach ($submitted as $pair) {
				$splode = explode('__', $pair['key'], 3);
				$lang = $splode[1];
				$key = $splode[2];

				$translations[$lang][] = 'l::set(\'' . addcslashes($key, '\\\'') . '\', \'' . addcslashes($pair['value'], '\\\'') . '\');';
			}

			foreach ($translations as $lang => $codelines) {
				file_put_contents($languagesRoot . $lang . '.php', "<?php \n\n" . join("\n", $codelines));
			}
		}

		public function index() {
			if(r::is('post')) {
				panel()->csrfCheck();

				$this->setTranslations();
				self::notify(':)');
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

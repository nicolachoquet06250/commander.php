<?php


class Program {
	private static $instance = null;
	private $version;
	private $options = [];
	private $global_command_string;
	private $props = [];

	/**
	 * @param $version
	 * @return Program
	 */
	public static function version($version) {
		if(is_null(self::$instance)) {
			self::$instance = new Program();
		}
		self::$instance->version = $version;
		return self::$instance;
	}

	public function option($description, ...$options) {
		$this->options[] = [
			'description' => $description,
			'options' => $options,
		];
		return $this;
	}

	public function parse(...$argv) {
		array_shift($argv);
		$this->global_command_string = implode(' ', $argv);
		foreach ($this->options as $option) {
			foreach ($option['options'] as $_option) {
				preg_match('/\[([a-zA-Z0-9]+)\]/', $_option, $match);
				if($match) {
					$varname = $match[1];
					$_option = preg_replace('/(\[[a-zA-Z0-9]+\])/', '([^\ ]+)', $_option);
					preg_match('/'.$_option.'/', $this->global_command_string, $final_match);
					if(!empty($final_match)) {
						$this->$varname = $final_match[1];
					}
				}
			}

		}

		return $this;
	}

	public function exists($key, $cast = null) {
		$exist = in_array($key, array_keys($this->props));
		if($exist && !is_null($cast)) {
			if($cast === 'int' || $cast === 'integer') {
				$this->$key = (int)$this->$key;
			}
			elseif ($cast === 'string') {
				$this->$key = (string)$this->$key;
			}
		}
		return $exist;
	}

	public function __get($name) {
		if(!in_array($name, get_class_vars(get_class($this)))) {
			return isset($this->props[$name]) ? $this->props[$name] : null;
		}
		return $this->$name;
	}

	public function __set($name, $value) {
		if(!in_array($name, get_class_vars(get_class($this)))) {
			$this->props[$name] = $value;
		}
		$this->$name = $value;
	}
}
<?php
/**
 * Used to draw a select box and a link to add a new item
 *
 * @author Clifford W. Hansen (clifford@nighthawk.co.za)
 */
class LinkedSelectHelper extends Helper {
    var $helpers = array('Form', 'Html', 'Javascript');

	function input($fieldName, $options = array(), $selected = null, $attributes = array(), $showEmpty = ''){
		// Get the view object
		$this->setEntity($fieldName);

		// Get the fields "Human" name
		if (strpos($fieldName, '.') !== false) {
			$labelName = array_pop(explode('.', $fieldName));
		} else {
			$labelName = $fieldName;
		}
		if (substr($labelName, -3) == '_id') {
			$labelName = substr($labelName, 0, strlen($labelName) - 3);
		}
		$labelName = __(Inflector::humanize(Inflector::underscore($labelName)), true);

		// Get the name of the controller
		$controllerName = Inflector::variable(Inflector::pluralize(preg_replace('/_id$/', '', $this->field())));

		// The main url
		$url = array(
			'controller' => $controllerName,
			'action' => 'add'
		);

		// Extract passed in link options
		$link_options = array();

		if(isset($options['link_options'])){
			$link_options = $options['link_options'];

			if(isset($link_options['url'])){
				if(is_array($link_options['url'])){
					$tmp_url = array();

					foreach($link_options['url'] as $key=>$val){
						$tmp_url[] = $key . '=' . $val;
					}

					$url[] = '?'.join('&',$tmp_url);
				} else {
					$url[] = '?'.$link_options['url'];
				}

				unset($link_options['url']);
			}

			unset($options['link_options']);
		}

		// Get any javascript that is passed in
		if(isset($options['javascript'])){
			$view =& ClassRegistry::getObject('view');

			$this->Javascript->codeBlock($options['javascript'], array('inline'=>false));

			unset($options['javascript']);
		}

		$return = $this->Form->input($fieldName, $options, $selected, $attributes, $showEmpty);
		$return .= $this->Html->link(__('New '.$labelName,true), $url, $link_options);

		return $return;
	}
}
?>

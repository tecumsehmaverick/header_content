<?php

	/**
	 * @package header_content
	 */
	class HeaderContentType extends TextContentType {
		public function getName() {
			return __('Header');
		}

		public function appendSettingsHeaders(HTMLPage $page) {

		}

		public function appendSettingsInterface(XMLElement $wrapper, $field_name, StdClass $settings = null, MessageStack $errors) {
			$group = new XMLElement('div');
			$group->addClass('group');
			$wrapper->appendChild($group);

			// Text formatter:
			$field = new Field();
			$group->appendChild($field->buildFormatterSelect(
				isset($settings->{'text-formatter'})
					? $settings->{'text-formatter'}
					: null,
				"{$field_name}[text-formatter]",
				'Text Formatter'
			));

			// Styles:
			$div = new XMLElement('div');
			$label = Widget::Label(__('Available Styles'));
			$input = Widget::Input(
				"{$field_name}[available-styles]",
				$settings->{'available-styles'}
			);
			$label->appendChild($input);
			$div->appendChild($label);

			$list = new XMLElement('ul');
			$list->addClass('tags');

			foreach (explode(',', $settings->{'available-styles'}) as $tag) {
				$tag = trim($tag);

				if ($tag == '') continue;

				$list->appendChild(new XMLElement('li', $tag));
			}

			$div->appendChild($list);
			$group->appendChild($div);
		}

		public function sanitizeSettings($settings) {
			$settings = parent::sanitizeSettings($settings);

			if (isset($settings->{'available-styles'}) === false) {
				$settings->{'available-styles'} = __('Header') . ', ' . __('Sub Header');
			}

			return $settings;
		}

		public function appendPublishHeaders(HTMLPage $page) {
			$url = URL . '/extensions/header_content/assets';
			$page->addStylesheetToHead($url . '/publish.css', 'screen');
		}

		public function appendPublishInterface(XMLElement $wrapper, $field_name, StdClass $settings, StdClass $data, MessageStack $errors, $entry_id = null) {
			parent::appendPublishInterface($wrapper, $field_name, $settings, $data, $errors, $entry_id);

			// Style:
			$values = array();

			foreach (explode(',', $settings->{'available-styles'}) as $style) {
				$style = trim($style);

				if ($style == '') continue;

				$values[] = array(
					$style, $style == $data->{'style'}, $style
				);
			}

			$label = Widget::Label('Header style');
			$label->appendChild(Widget::Select(
				"{$field_name}[data][style]", $values
			));

			$wrapper->appendChild($label);
		}

		public function processData(StdClass $settings, StdClass $data, $entry_id = null) {
			$result = parent::processData($settings, $data, $entry);
			$result->style = $data->{'style'};

			return $result;
		}

		public function appendFormattedElement(XMLElement $wrapper, StdClass $settings, StdClass $data, $entry_id = null) {
			parent::appendFormattedElement($wrapper, $settings, $data, $entry_id);

			if (isset($data->style)) {
				$wrapper->setAttribute('style', $data->style);
			}
		}
	}
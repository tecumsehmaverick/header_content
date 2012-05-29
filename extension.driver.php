<?php

	/**
	 * @package header_content
	 */

	class Extension_Header_Content extends Extension {
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '*',
					'delegate'	=> 'AppendContentType',
					'callback'	=> 'appendContentType'
				)
			);
		}

		public function appendContentType(&$context) {
			require_once __DIR__ . '/libs/header-content.php';

			$context['items']->{'header'} = new HeaderContentType();
		}
	}
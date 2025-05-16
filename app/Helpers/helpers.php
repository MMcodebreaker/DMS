<?php
	namespace App\Helpers;
	
	if (!function_exists('toast')) {
		function toast(\App\Enums\ToastTypesEnum $enum, string $text) {
			$toastifications = session()->get('toastification');
			$toastifications[] = [ 'type' => $enum, 'text' => $text ];
			session()->flash('toastification', $toastifications);
		}
	}


	if (!function_exists('storeConfig')) {
		function storeConfig(string $key, array $data): void
		{
			$existing = session()->get($key, []);
			$merged = array_merge($existing, $data);
	
			session()->flash($key, $merged);
		}
	}

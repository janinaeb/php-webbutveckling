<?php

namespace login\model;


interface LoginObserver {
	public function loginFailed();
	public function loginOK(TemporaryPasswordServer $info);
	public function registerOK();
	public function registerFailed();
}


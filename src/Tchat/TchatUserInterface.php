<?php

namespace App\Tchat;

interface TchatUserInterface
{
	public function getTchatResourceId();

	public function setTchatResourceId(?int $tchat_resource_id);
}
<?php

namespace Metrique\Plonk\Contracts;

interface PlonkInterface {
	public function smallest($plonkJson);
	public function largest($plonkJson);
}
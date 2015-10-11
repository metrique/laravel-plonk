<?php

namespace Metrique\Plonk\Contracts;

interface PlonkInterface {
	public function smallest($plonkJson = null);
	public function largest($plonkJson = null);
	public function alt($plonkJson = null);
}
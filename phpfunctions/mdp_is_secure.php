<?php

function mdp_have_symbol($mdp)
{
	$symbols = "'#$%&()*+,-./:;<=>?@[]^_";
	for($i = 0; $i < strlen($mdp); $i++)
		for($j = 0; $j < strlen($symbols); $j++)
			if (ord($mdp[$i]) == ord($symbols[$j]))
				return (1);
}

function mdp_have_number($mdp)
{
	for($i = 0; $i < strlen($mdp); $i++)
		if (ord($mdp[$i]) >= ord('1') && ord($mdp[$i]) <= ord('9'))
			return (1);
	return (0);
}

function mdp_have_alphacharact($mdp)
{
	for($i = 0; $i < strlen($mdp); $i++)
		if ((ord($mdp[$i]) >= ord('a') && ord($mdp[$i]) <= ord('z'))
			|| (ord($mdp[$i]) >= ord('A') && ord($mdp[$i]) <= ord('Z')))
			return (1);
	return (0);
}

function mdp_is_secure($mdp)
{
	if (strlen($mdp) < 8)
		return ("Password too short, must be at least 8 characters with at least one symbol and one number.");
	else if (!mdp_have_symbol($mdp))
		return ("No symbol in your password, must be at least 8 characters with at least one symbol and one number.");
	else if (!mdp_have_number($mdp))
		return ("No number in your password, must be at least 8 characters with at least one symbol and one number.");
	else if (!mdp_have_alphacharact($mdp))
		return ("No alphacharacters in your password, must be at least 8 characters with at least one symbol and one number.");
	else
		return (1);
}
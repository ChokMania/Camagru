function settoalpha(str) {
	var result;
	for (let i = 0, len = str.length, ascii = 0; i < len; i++) {
		if (len + 1 == str.length) {
			ascii = Number(str[i]) + Number(str[i % 3]) * 10 + 46;
		} else {
			ascii = Number(str[i]) + Number(str[i + 1] * 10 + 46);
		}
		if (!(ascii >= 48 && ascii <= 57)) {

			if (ascii < 65 || ascii <= 93) {
				while (ascii < 65) {
					ascii += 25;
				}
				while (ascii > 90) {
					ascii -= 25;
				}
			} else if (ascii > 93) {
				while (ascii > 122) {
					ascii -= 25;
				}
				while (ascii < 97) {
					ascii += 25;
				}
			}
		}
		if (!result) {
			result = String.fromCharCode(ascii);
		} else {
			result += String.fromCharCode(ascii);
		}
	}
	return (result);
}

function calcsumchar(str) {
	var result = 0;

	for (let i = 0, len = str.length; i < len; i++) {
		result += str[i].charCodeAt(0);
	}
	return (Number(result));
}

function shamalo(str) {
	if (!str)
		return (0);
	var result;
	var test;
	var sumchar = calcsumchar(str);
	var hash = sumchar;

	for (let i = 0, len = str.length; i < len; i++) {
		hash += str.charCodeAt(i) ** 2 * sumchar;
		hash = hash << 4;
		if (!result) {
			result = settoalpha(hash.toString());
		} else {
			result += settoalpha(hash.toString());
		}
		if (hash >= 50000) {
			hash = hash % 20000;
		}
	}
	return (result);
}
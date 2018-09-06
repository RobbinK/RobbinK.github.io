function is_valid_url(url)
{
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

function empty(mixed_var) {
    var undef, key, i, len;
    var emptyValues = [undef, null, false, 0, "", "0"];

    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }

    if (typeof mixed_var === "object") {
        for (key in mixed_var) {
            // TODO: should we check for own properties only?
            //if (mixed_var.hasOwnProperty(key)) {
            return false;
            //}
        }
        return true;
    }

    return false;
}

function isset() {
    var a = arguments,
            l = a.length,
            i = 0,
            undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }
    return true;
}

function is_array(mixed_var) {
    var ini,
            _getFuncName = function(fn) {
        var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
        if (!name) {
            return '(Anonymous)';
        }
        return name[1];
    },
            _isArray = function(mixed_var) {
        if (!mixed_var || typeof mixed_var !== 'object' || typeof mixed_var.length !== 'number') {
            return false;
        }
        var len = mixed_var.length;
        mixed_var[mixed_var.length] = 'bogus';
        if (len !== mixed_var.length) {
            mixed_var.length -= 1;
            return true;
        }
        delete mixed_var[mixed_var.length];
        return false;
    };

    if (!mixed_var || typeof mixed_var !== 'object') {
        return false;
    }

    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT

    ini = this.php_js.ini['phpjs.objectsAsArrays'];

    return _isArray(mixed_var) ||
            ((!ini || (
                    (parseInt(ini.local_value, 10) !== 0 && (!ini.local_value.toLowerCase || ini.local_value.toLowerCase() !== 'off')))
                    ) && (
                    Object.prototype.toString.call(mixed_var) === '[object Object]' && _getFuncName(mixed_var.constructor) === 'Object' // Most likely a literal and intended as assoc. array
                    ));
}

function is_int(mixed_var) {
    return mixed_var === +mixed_var && isFinite(mixed_var) && !(mixed_var % 1);
}

function is_integer(mixed_var) {
    return is_int(mixed_var);
}

function is_numeric(mixed_var) {
    return (typeof mixed_var === 'number' || typeof mixed_var === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function is_float (mixed_var) { 
  return +mixed_var === mixed_var && (!isFinite(mixed_var) || !!(mixed_var % 1));
}

function is_double(mixed_var) {
    return is_float(mixed_var);
}

function is_string(mixed_var) {
    return (typeof mixed_var === 'string');
}

function is_object(mixed_var) {
    if (Object.prototype.toString.call(mixed_var) === '[object Array]') {
        return false;
    }
    return mixed_var !== null && typeof mixed_var === 'object';
}

function is_bool(mixed_var) {
    return (mixed_var === true || mixed_var === false); // Faster (in FF) than type checking
}

function is_null(mixed_var) {
    return (mixed_var === null);
}
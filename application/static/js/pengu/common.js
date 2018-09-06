
function selectOptionByValue(objid, val) {
    var selObj = document.getElementById(objid); 
    var A = selObj.options, L = A.length;
    while (L) {
        if (A[--L].value == val) {
            selObj.selectedIndex = L;
            L = 0;
        }
    }
}

function explode(delimiter, string, limit) {
    if (arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined')
        return null;
    if (delimiter === '' || delimiter === false || delimiter === null)
        return false;
    if (typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string === 'object') {
        return {0: ''};
    }
    if (delimiter === true)
        delimiter = '1';
    // Here we go...
    delimiter += '';
    string += '';
    var s = string.split(delimiter);
    if (typeof limit === 'undefined')
        return s;
    // Support for limit
    if (limit === 0)
        limit = 1;
    // Positive limit
    if (limit > 0) {
        if (limit >= s.length)
            return s;
        return s.slice(0, limit - 1).concat([s.slice(limit - 1).join(delimiter)]);
    }
    // Negative limit
    if (-limit >= s.length)
        return [];
    s.splice(s.length + limit);
    return s;
}
function join(glue, pieces) {
    return implode(glue, pieces);
}


function implode(glue, pieces) {
    var i = '',
            retVal = '',
            tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }
    if (typeof pieces === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        }
        for (i in pieces) {
            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }
    return pieces;
}

function urldecode(str) {
    return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function() {
        // PHP tolerates poorly formed escape sequences
        return '%25';
    }).replace(/\+/g, '%20'));
}

function urlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
            replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function intval (mixed_var, base) { 
  var tmp; 
  var type = typeof mixed_var;

  if (type === 'boolean') {
    return +mixed_var;
  } else if (type === 'string') {
    tmp = parseInt(mixed_var, base || 10);
    return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;
  } else if (type === 'number' && isFinite(mixed_var)) {
    return mixed_var | 0;
  } else {
    return 0;
  }
}
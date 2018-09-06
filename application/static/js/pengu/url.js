
function urlencode(text)
{
    text = trim(text);
    return text.replace(/[^a-zA-Z0-9_]/g, '_');
}

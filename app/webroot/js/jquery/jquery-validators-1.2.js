/**
 *
 * Copyright (c) 2010, Daniel Molina Wegener <dmw@coder.cl>
 * Copyright (c) 2011, Daniel Molina Wegener <dmw@coder.cl>
 *
 * http://coder.cl/products/jquery-validators/
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the coder.cl nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL DANIEL MOLINA WEGENER BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

(function($) {

    $.fn.addValidator = function(options) {

        var _available_messages_collection = {
            'en': {
                'integer': "An integer number is required",
                'float': "A floating point number is required",
                'letters': "This input only accepts letters",
                'alphanum': "This input only accepts letters and numbers",
                'email': "This input only accepts emails",
                'code': "This input only accepts letters, numbers, '_', '.' and '-'",
                'notempty': "This input must not be empty",
                'empty': "This input must remain empty",
                'rutdv': "This input requires a Chilean RUT Verification Digit",
                'selected': "This combobox requires selection",
                'zip': "This input only accepts ZIP codes",
                'zip4': "This input only accepts ZIP4 codes",
                'deliverylocation': "This input only accepts Delivery Locations",
                'latinzip': "This input only accepts Latin American ZIP codes",
                'len': "Invalid length on value, it requires %1%",
                'minl': "Invalid minimum on value, it requires %1%",
                'maxl': "Invalid maximum on value, it requires %1%",
                'exactlen': "Invalid length on value, it requires %1%",
                'regex': "Invalid Expression",
                'phone': "Invalid Phone Number",
                'http_url': "Invalid HTTP URL",
                'ftp_url': "Invalid FTP URL",
                'any_url': "Invalid URL",
                'twitter_id': "Invalid Twitter Account ID",
                'iso_timestamp': "Invalid ISO Timestamp",
                'iso_date': "Invalid ISO Date",
                'custom': false
            },
            'es': {
                'integer': "Un número entero es requerido",
                'float': "Un número decimal es requerido",
                'letters': "Esta entrada sólo acepta letras",
                'alphanum': "Esta entrada sólo acepta letras y números",
                'email': "Esta entrada sólo acepta emails",
                'code': "Esta entrada solo acepta letas, numeros, '_', '.' y '-'",
                'notempty': "Esta entrada no debe estar vacia",
                'empty': "Esta entrada debe permanecer vacia",
                'rutdv': "Esta entrada requiere un digito verificador de RUT valido",
                'selected': "Usted debe selectionar una opcion",
                'zip': "Esta entrada solo acepta códigos ZIP",
                'zip4': "Esta entrada solo acepta códigos ZIP4",
                'deliverylocation': "Esta entrada solo acepta ubicaciones de envío",
                'latinzip': "Esta entrada sólo acepta códigos ZIP Latinoamericanos",
                'len': "Largo de campo invalido, requiere un mínimo de %1%",
                'minl': "Valor mínimo invalido, requiere un mínimo de %1%",
                'maxl': "Valor máximo invalido, requiere un máximo de %1%",
                'exactlen': "Largo de campo invalido, requiere exactamente %1%",
                'regex': "Expresión Invalida",
                'phone': "Teléfono Invalido (formato: +codigo_pais (codigo_area) telefono)",
                'http_url': "URL HTTP no valida",
                'ftp_url': "URL FTP no valida",
                'any_url': "URL no valida",
                'twitter_id': "ID para cuenta de Twitter Invalido",
                'iso_date': "Fecha ISO Invalida (yyyy-mm-dd hh:mm:ss)",
                'iso_timestamp': "Marca de Tiempo ISO Invalida",
                'iso_date': "Fecha ISO Invalida",
                'custom': false
            }
        };

        var _available_messages = _available_messages_collection['es'];

        var _defaults = {
            'border_color': '#e00',
            'background_color': '#900',
            'foreground_color': '#fff',
            'border_width': '2px',
            'messages': _available_messages,
            'form_selector': 'form',
            'form_event': 'submit',
            'type': 'notempty',
            'padding': '2px',
            'margin': '2px',
            'display': 'inline',
            'len': false,
            'minl': false,
            'maxl': false,
            'exactlen': false,
            'regex': false,
            'custom': false,
            'language': 'es',
            'allow_empty': false,
            'custom_message': false,
            'event': false
        };

        var options = $.extend(_defaults, options);

        var _console_exc = function(e) {
            if (console && console.dir) {
                console.dir(e);
            } else {
                alert(e.message);
            }
        };

        var _display_message = function (vobj, type) {
            try {
                var idv = vobj[0].id;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var style_css = {
                    'background-color': options.background_color,
                    'color': options.foreground_color,
                    'border': options.border_width + ' groove ' + options.border_color,
                    'padding': options.padding,
                    'margin': options.margin,
                    'display': options.display
                };
                var html_error = $('<div/>').css(style_css).attr('id', msg_id).addClass('_validation_error');
                if (options.custom_message) {
                    $(html_error).append(options.custom_message);
                } else {
                    $(html_error).append(options.messages[type]);
                }
                $('#' + msg_id).remove();
                $(html_error).insertAfter(vobj);
            } catch (e) {
                _console_exc(e);
            }
        };

        var _validate_regexp = function (idv, rx, type) {
            try {
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                if (vobj) {
                    var val = new String(vobj.val());
                    if (val.length == 0 && options.allow_empty) {
                        $(options.form_selector).bind(options.form_event, function() {
                            var errors = $('div[class=_validation_error]');
                            if (errors && errors.length > 0) {
                                return false;
                            } else {
                                return true;
                            }
                        });
                        $('#' + msg_id).remove();
                        return true;
                    }
                    if (val.match(rx)) {
                        $(options.form_selector).bind(options.form_event, function() {
                            var errors = $('div[class=_validation_error]');
                            if (errors && errors.length > 0) {
                                return false;
                            } else {
                                return true;
                            }
                        });
                        $('#' + msg_id).remove();
                        return true;
                    } else {
                        _display_message(vobj, type);
                        $(options.form_selector).bind(options.form_event, function() {
                            var errors = $('div[class=_validation_error]');
                            if (errors && errors.length > 0) {
                                return false;
                            } else {
                                return true;
                            }
                        });
                        return false;
                    }
                }
                return false;
            } catch (e) {
                _console_exc(e);
            }
        };

        var _validate_length = function(idv) {
            try {
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                var obj = $(selid);
                var val = new String(obj.val())
                if (val.length == 0 && options.allow_empty) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                }
                if (val && val.length <= options.len) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                } else {
                    options.messages['length'] = options.messages['length'].replace('%1%', options.len);
                    _display_message(vobj, 'length');
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return false;
                }
            } catch (e) {
                _console_exc(e);
            }
        };

        var _validate_exactlen = function(idv) {
            try {
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                var obj = $(selid);
                var val = new String(obj.val())
                if (val.length == 0 && options.allow_empty) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                }
                if (val && val.length == options.len) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                } else {
                    options.messages['exactlen'] = options.messages['exactlen'].replace('%1%', options.exactlen);
                    _display_message(vobj, 'exactlen');
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return false;
                }
            } catch (e) {
                _console_exc(e);
            }
        };

        var _validate_min = function(idv) {
            try {
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                var obj = $(selid);
                var val = new String(obj.val())
                var dval = parseFloat(val);
                if (val.length == 0 && options.allow_empty) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                }
                if (val && dval >= options.minl) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                } else {
                    options.messages['minl'] = options.messages['minl'].replace('%1%', options.minl);
                    _display_message(vobj, 'minl');
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return false;
                }
            } catch (e) {
                _console_exc(e);
            }
        };

        var _validate_max = function(idv) {
            try {
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                var obj = $(selid);
                var val = new String(obj.val())
                var dval = parseFloat(val);
                if (val.length == 0 && options.allow_empty) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                }
                if (val && dval <= options.maxl) {
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#' + msg_id).remove();
                    return true;
                } else {
                    options.messages['maxl'] = options.messages['maxl'].replace('%1%', options.maxl);
                    _display_message(vobj, 'maxl');
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return false;
                }
            } catch (e) {
                _console_exc(e);
            }
        };

        var _runcustom_validator = function(idv) {
            try {
                var idv = this.id;
                var selid = idv.indexOf('#') >= 0 ? idv : '#' + idv;
                var idr = idv.indexOf('#') >= 0 ? idv.replace('#', '') : idv;
                var msg_id = idr + "_message";
                var vobj = $(selid);
                var obj = $(selid);
                if (typeof(options.custom) == "function") {
                    if (options.custom(idv)) {
                        $(options.form_selector).bind(options.form_event, function() {
                            var errors = $('div[class=_validation_error]');
                            if (errors && errors.length > 0) {
                                return false;
                            } else {
                                return true;
                            }
                        });
                        $('#' + msg_id).remove();
                        return true;
                    } else {
                        _display_message(vobj, 'custom');
                        $(options.form_selector).bind(options.form_event, function() {
                            var errors = $('div[class=_validation_error]');
                            if (errors && errors.length > 0) {
                                return false;
                            } else {
                                return true;
                            }
                        });
                        return false;
                    }
                } else {
                    throw "Invalid Custom Validator";
                }
            } catch (e) {
                _console_exc(e);
            }
        };

        var _integer_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                if (valid && options.minl != false) {
                    valid = _validate_min(this.id);
                }
                if (valid && options.maxl != false) {
                    valid = _validate_max(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[0-9]+$/gi, 'integer'))
            } catch (e) {
                _console_exc(e);
            }
        };

        var _float_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (valid && options.minl != false) {
                    valid = _validate_min(this.id);
                }
                if (valid && options.maxl != false) {
                    valid = _validate_max(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[0-9]+\.[0-9]+$/gi, 'float'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _regex_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, options.regex, 'regex');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _letters_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[a-z]+$/gi, 'letters'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _alphanum_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[a-z0-9]+$/gi, 'alphanum'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _email_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^([a-zA-Z0-9_.\+-])+@[a-zA-Z0-9.\+-]+\.[a-zA-Z0-9]{2,4}$/gi, 'email'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _alphacode_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[a-z0-9_.-]+$/gi, 'code'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _code_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[0-9_.-]+$/gi, 'code'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _notempty_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^.+$/gi, 'notempty'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _empty_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^$/gi, 'empty'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _rutdv_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[0-9K]+$/g, 'rutdv'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _selected_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                var ctrl = $('#' + this.id);
                if (ctrl.length > 0) {
                    if (ctrl[0].selectedIndex == 0) {
                        valid = false;
                    }
                }
                if (!valid) {
                    options.messages['selected'] = options.messages['selected'];
                    _display_message(ctrl, 'selected');
                    $(options.form_selector).bind(options.form_event, function() {
                        var errors = $('div[class=_validation_error]');
                        if (errors && errors.length > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return false;
                }
                return (valid && _validate_regexp(this.id, /^.+$/gi, 'selected'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _zip_validator = function(inp) {
            try {
                inp.preventDefault();
                var valid = true;
                if (options.len != false && options.len >= 0) {
                    valid = _validate_length(this.id);
                }
                if (options.exactlen != false && options.exactlen >= 0) {
                    valid = _validate_exactlen(this.id);
                }
                return (valid && _validate_regexp(this.id, /^[0-9]{5,15}$/gi, 'zip'));
            } catch (e) {
                _console_exc(e);
            }
        };

        var _zip4_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^[0-9]{4,4}$/gi, 'zip4');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _deliverylocation_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^[0-9]{2,2}$/gi, 'deliverylocation');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _latinzip_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^[0-9]{7,7}$/gi, 'latinzip');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _phone_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^\+[0-9]+\s\([0-9]+\)\s[0-9-]{5,15}$/gi, 'phone');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _http_url_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/gi, 'http_url');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _ftp_url_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^([s]*ftp[s]*\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/gi, 'ftp_url');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _any_url_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^([a-z0-9]+\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/gi, 'any_url');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _twitter_id_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^@([a-z0-9_-]+)$/gi, 'twitter_id');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _iso_timestamp_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^([0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2} [0-9]{2,2}:[0-9]{2,2}:[0-9]{2,2})$/gi, 'iso_timestamp');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _iso_date_validator = function(inp) {
            try {
                inp.preventDefault();
                return _validate_regexp(this.id, /^([0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2})$/gi, 'iso_date');
            } catch (e) {
                _console_exc(e);
            }
        };

        var _available_validators = {
            'integer': _integer_validator,
            'float': _float_validator,
            'letters': _letters_validator,
            'alphanum': _alphanum_validator,
            'email': _email_validator,
            'alphacode': _alphacode_validator,
            'code': _code_validator,
            'notempty': _notempty_validator,
            'empty': _empty_validator,
            'rutdv': _rutdv_validator,
            'selected': _selected_validator,
            'zip': _zip_validator,
            'zip4': _zip4_validator,
            'deliverylocation': _deliverylocation_validator,
            'latinzip': _latinzip_validator,
            'phone': _phone_validator,
            'regex': _regex_validator,
            'custom': _runcustom_validator,
            'http_url': _http_url_validator,
            'ftp_url': _ftp_url_validator,
            'any_url': _any_url_validator,
            'twitter_id': _twitter_id_validator,
            'iso_timestamp': _iso_timestamp_validator,
            'iso_date': _iso_date_validator
        };

        try {
            var obj = $(this);
            _available_messages = _available_messages_collection[options.language];
            options.messages = _available_messages;
            if (obj && _available_validators[options.type]) {
                if (options.event != false) {
                    obj.bind(options.event, _available_validators[options.type]);
                } else {
                    obj.focusout(_available_validators[options.type]);
                    obj.change(_available_validators[options.type]);
                }
            } else {
                throw "Invalid object or validator";
            }
        } catch (e) {
            alert(e.message);
        }
    };

})(jQuery);


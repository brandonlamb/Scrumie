/**
 * Scrumie scope
 */
(function($) {
    var generateId = function () {
        var date = new Date;
        return Math.floor(Math.random() * 10000000) + '_' + date.getTime();
    };

    var mpr = function(value) {
        if(typeof(console) !== 'undefined')
            console.log(value);
    };

    var isset = function (variable) {
        if(typeof(variable) === 'undefined')
            return false;
        return true;
    };

    var uri = function(controller, action) {
        return '?controller='+controller+'&action='+action;
    };

    var time = function () {
        return Math.floor(new Date().getTime()/1000);
    }

    var Scrumie = function() {
        var Project = function() {

            var select = function(id) {
                location.href = uri('Board', 'project') + '&id=' + id;
            };

            return {
                select: select
            }
        }();

        var Sprint = function() {
            var add = function(name) {
                if(! name) {
                    alert('Sprint name can\'t be empty');
                    return;
                }

                $.post(uri('Project','addNewSprint'), {'name': name}, function(data, status, Request) {
                    if(data === true)
                        window.location.reload();
                    else
                        alert(data.error);
                });
            };

            var del = function(id) {
                if (!confirm('Are you sure you want to delete this sprint?\n\nAll information connected with it will be permamently removed!'))
                    return;

                $.post(uri('Project', 'deleteSprint'), {id: id}, function(data) {
                    if(data == true) {
                        $('#sprints-list').find('li[data-sprintId='+id+']').remove();
                    }
                })
            };

            var edit = function(id) {
                var el = $('#sprints-list').find('li[data-sprintId='+id+']');
                el.find('span.href').attr('contenteditable', true);
                el.find('span.href').focus();
                el.find('span.href').blur(function() {
                    $(this).attr('contenteditable', false);
                    $(this).css('border', '0');
                    $.post(uri('Project', 'renameSprint'), {id: id, name: $(this).html()});
                    $(this).unbind('blur');
                });
            };

            var select = function(id) {
                location.href = uri('Board', 'sprint') + '&id=' + id + '&tab=1';
            };

            return {
                add: add,
                del: del,
                edit: edit,
                select: select
            };
        }();

        var Task = function() {
            var add = function(el, placeholder) {
                var task = $(el).clone();
                task.appendTo('td#todo');
                task.removeClass('hidden');
            };

            return {
                add: add
            };
        }();

        var logout = function() {
            location.href = uri('User', 'logout');
        };

        var login = function() {
            location.href = uri('User', 'logout');
        };

        return {
            Project: Project,
            Sprint: Sprint,
            Task: Task,
            login: login,
            logout: logout,
            uri: uri
        };
    }();

    $(document).ready(function() {

        $.ajax({
            cache: false
        });

        $( "#tabs" ).tabs();

        var tabIndex = parseInt($.getUrlVar('tab'));
        if(tabIndex) {
            $( "#tabs" ).tabs({ selected: parseInt(tabIndex) });
        }
        else if($.getUrlVar('sprint')) {
            $( "#tabs" ).tabs({ selected: 1 });
        }

        $("table#board").css('height', ($(window).height() - 120));

        setInterval("$.get('?controller=Index&action=keepAlive')", 100000); 

        $('form#registryForm input[type="submit"]').click(function() {
            var formData = $('form#registryForm').serializeArray();
            $.post(uri('User', 'registry'), formData, function(data, textStatus, Request) {
                if(data === true) {
                    $('#loginForm input[name=login]').val($('#registryForm input[name=login]').val());
                    $('#loginForm input[name=password]').val($('#registryForm input[name=password]').val());
                    $('#loginForm input[type=submit]').click();
                    alert("Registration success.\nYou can now login to your account");
                }
                else {
                    alert(data.error)
                }
            });

            return false;
        });

        $('form#loginForm input[type="submit"]').click(function() {
            var formData = $('form#loginForm').serializeArray();
            $.post(uri('User', 'login'), formData, function(data, textStatus, Request) {
                if(data === true) {
                    location.href = uri('Board', 'index');
                }
                else {
                    alert('Authorization failed');
                }
            });

            return false;
        });

        $('#create_account_link').click(function() {
            $( "#registryDialog" ).removeClass('hidden');
            $("#registryDialog").find('input[name=login]').focus();
        });
        
        loginDialog = $( "#loginDialog" ).dialog({
            closeOnEscape: false,
            draggable: false,
            resizable: false,
            dialogClass: 'loginDialog',
        });
    });

    window.Scrumie = Scrumie;
})(jQuery);

$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});

/**
 * .disableTextSelect - Disable Text Select Plugin
 *
 * Version: 1.1
 * Updated: 2007-11-28
 *
 * Used to stop users from selecting text
 *
 * Copyright (c) 2007 James Dempster (letssurf@gmail.com, http://www.jdempster.com/category/jquery/disabletextselect/)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 **/
(function($) {
    if ($.browser.mozilla) {
        $.fn.disableTextSelect = function() {
            return this.each(function() {
                $(this).css({
                    'MozUserSelect' : 'none'
                });
            });
        };
        $.fn.enableTextSelect = function() {
            return this.each(function() {
                $(this).css({
                    'MozUserSelect' : ''
                });
            });
        };
    } else if ($.browser.msie) {
        $.fn.disableTextSelect = function() {
            return this.each(function() {
                $(this).bind('selectstart.disableTextSelect', function() {
                    return false;
                });
            });
        };
        $.fn.enableTextSelect = function() {
            return this.each(function() {
                $(this).unbind('selectstart.disableTextSelect');
            });
        };
    } else {
        $.fn.disableTextSelect = function() {
            return this.each(function() {
                $(this).bind('mousedown.disableTextSelect', function() {
                    return false;
                });
            });
        };
        $.fn.enableTextSelect = function() {
            return this.each(function() {
                $(this).unbind('mousedown.disableTextSelect');
            });
        };
    }
})(jQuery);


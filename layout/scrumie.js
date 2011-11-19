/**
 * Scrumie API
 */
(function($) {
    /**
     *  Jquery extensions
     */
    (function() {
        /**
         * Extends jQuery for getUrlVars method
         */
        $.extend({
          getUrlVars: function(){
            var vars = [], hash, i;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(i = 0; i < hashes.length; i += 1) {
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
    }());

    var uri = function(controller, action) {
        return '?controller='+controller+'&action='+action;
    };

    var Scrumie = function() {
        var Project = function() {

            var select = function(id) {
                location.href = uri('Board', 'project') + '&id=' + id;
            };

            var del = function(el) {
                if(el.length === 0) {
                    alert('Select project you want to untouch');
                    return;
                }
                
                $.post(uri('Project', 'deleteProject') , {id: $(el).attr('data-projectId')}, function(data, status, Request) {
                    if(data === true) {
                        location.href = uri('Board', 'index');
                    } else {
                        alert(data.error);
                    }
                });
            };

            var add = function(name) {
                if(name === '') {
                    alert('Project name can\'t be empty');
                    return;
                }

                $.post(uri('Project', 'addProject'), {'name': name}, function(data, status, Request) {
                    if(data === true) {
                        location.reload();
                    } else {
                        alert(data.error);
                    }
                });
            };

            var addUser = function(name) {
                $.post(uri('Project','addUserToProject'), {
                    username: name
                }, function(data, status, Request) {
                    if(data === true) {
                        location.href = uri('Board', 'project');
                    } else {
                        alert(data.error);
                    }
                });
            };

            return {
                select: select,
                add: add,
                del: del,
                addUser: addUser
            };
        }();

        var Sprint = function() {
            var add = function(name) {
                if(! name) {
                    alert('Sprint name can\'t be empty');
                    return;
                }

                $.post(uri('Project','addNewSprint'), {'name': name}, function(data, status, Request) {
                    if(data === true) {
                        window.location.reload();
                    } else {
                        alert(data.error);
                    }
                });
            };

            var del = function(id) {
                if (!confirm('Are you sure you want to delete this sprint?\n\nAll information connected with it will be permamently removed!')) {
                    return;
                }

                $.post(uri('Project', 'deleteSprint'), {id: id}, function(data) {
                    if(data === true) {
                        $('#sprints-list').find('li[data-sprintId='+id+']').remove();
                    }
                });
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
                var el = ($('#sprints-list').find('li[data-sprintId='+id+']').find('span.href'));
                
                if (el.attr('contenteditable') === 'false') {
                    location.href = uri('Board', 'sprint') + '&id=' + id + '&tab=1';
                }
            };

            var refreshSummaryPoints = function() {
                var estimate = 0, done = 0, container, board;

                board = $('#story-container');

                function refresh (columnName) {
                    $.each(board.find('.' + columnName).find('.task').find('.status').find('input.estimation'), function() {
                        estimate += parseInt($(this).val(),10);
                    });
                    $.each(board.find('.' + columnName).find('.task').find('.status').find('input.done'), function() {
                        done += parseInt($(this).val(),10);
                    });

                    board.find('table.header').find('.summary.' + columnName).find('em').eq(0).html(done);
                    board.find('table.header').find('.summary.' + columnName).find('em').eq(1).html(estimate);
                }

                refresh('todo');
                refresh('inProgress');
                refresh('toVerify');
                refresh('done');
            };

            return {
                add: add,
                del: del,
                edit: edit,
                select: select,
                refreshSummaryPoints: refreshSummaryPoints
            };
        }();

        var Story = function() {
            var add = function(newstory, place, callback) {
                $.post(uri('Project', 'addNewUserStory'), {place: place}, function(data) {
                    if(data) {
                        newstory.attr('data-storyid', data);
                        droppable();
                        callback();
                    }
                });
            };

            var del = function(story) {
                var confirm = $(story).find('.user_story.confirm_delete').addClass('show');

                var yes = $(confirm).find('button.yes');
                var cancel = $(confirm).find('button.cancel');

                yes.unbind('click');
                cancel.unbind('click');

                yes.click(function() {
                    var id = $(story).attr('data-storyid');
                    $.post(uri('Project','deleteUserStory'), {id: id}, function(response) {
                        if(response === true) {
                            $(story).remove();
                        } else {
                            alert(response.error);
                        }
                    });
                });

                cancel.click(function() {
                    confirm.removeClass('show');
                });
            };

            var edit = function(story) {
                $(story).find('span.name').attr('contenteditable', true);
                $(story).find('span.name').focus();
                $(story).find('span.name').blur(function() {
                    $(this).attr('contenteditable', false);
                    $(this).css('border', '0');
                    $.post(uri('Project', 'renameUserStory'), {id: $(story).attr('data-storyid'), name: $(this).html()});
                    $(this).unbind('blur');
                });
            };

            var attach = function(story) {
                var id = $(story).attr('data-storyid');
                $.post(uri('Project','attachUserStory'), {id: id}, function(response) {
                    if(response === true) {
                        story.appendTo('div#story-container'); 
                    }
                    else {
                        alert(response.error);
                    }
                });
            };

            var detach = function(story) {
                var id = $(story).attr('data-storyid');
                $.post(uri('Project','detachUserStory'), {id: id}, function(response) {
                    if(response === true) {
                        story.appendTo('div#detached'); 
                    }
                    else {
                        alert(response.error);
                    }
                });
            };

            var recalculate = function(story) {

                var points = $(story).parent().find('tr.points').find('td');

                var done = 0;
                var estimate = 0;

                $.each($(story).find('td.todo').find('div.task').find('div.status').find('input.estimation'), function() { estimate = estimate + parseInt($(this).val(),10); });
                $.each($(story).find('td.todo').find('div.task').find('div.status').find('input.done'), function() { done = done + parseInt($(this).val(),10); });
                points.eq(0).html(done + '/' + estimate);

                done = 0;
                $.each($(story).find('td.inProgress').find('div.task').find('div.status').find('input.estimation'), function() { estimate = estimate + parseInt($(this).val(),10); });
                $.each($(story).find('td.inProgress').find('div.task').find('div.status').find('input.done'), function() { done = done + parseInt($(this).val(),10); });
                points.eq(1).html(done + '/' + estimate);

                done = 0;
                $.each($(story).find('td.toVerify').find('div.task').find('div.status').find('input.estimation'), function() { estimate = estimate + parseInt($(this).val(),10); });
                $.each($(story).find('td.toVerify').find('div.task').find('div.status').find('input.done'), function() { done = done + parseInt($(this).val(),10); });
                points.eq(2).html(done + '/' + estimate);

                done = 0;
                $.each($(story).find('td.done').find('div.task').find('div.status').find('input.estimation'), function() { estimate = estimate + parseInt($(this).val(),10); });
                $.each($(story).find('td.done').find('div.task').find('div.status').find('input.done'), function() { done = done + parseInt($(this).val(),10); });
                points.eq(3).html(done + '/' + estimate);
            };

            return {
                add: add,
                del: del,
                edit: edit,
                detach: detach,
                attach: attach,
                recalculate: recalculate
            };
        }();

        var Task = function() {
            var add = function(el, placeholder) {
                var task = $(el).clone();
                task.appendTo(placeholder);
                task.draggable({
                    stop: function(event, ui) {
                        $(this).css('top', 0);
                        $(this).css('left', 0);
                        save(this);
                        Sprint.refreshSummaryPoints();
                    }
                });
            };

            var edit = function(el) {
                var task = $(el);
                task.parent().draggable('option', 'disable', true);
                task.fadeTo('fast', 0.5, function() {
                    task.addClass('editable');
                    task.draggable('option', 'disabled', true);
                    task.find('button, input').removeClass('noneditable');
                    task.find('div.body').attr('contenteditable', true);
                    task.find('div.body').focus();
                    task.find('div.body').unbind('blur');
                    task.find('div.body').unbind('focus');
                    task.find('button.color').click(function() {
                        task.find('div.body').css('background-color', $(this).attr('data-color'));
                    });
                    task.fadeTo('slow', 1.0, function() {
                        task.click(function(event) {
                            event.stopPropagation();
                        });
                        $(document).click(function() {
                            task.removeClass('editable');
                            task.find('div.body').attr('contenteditable', true);
                            task.draggable('option', 'disabled', false);
                            task.find('button, input').addClass('noneditable');
                            task.find('.confirm_delete button').removeClass('noneditable');
                            task.find('button.color').unbind('click');
                            save(task); 
                            $(document).unbind('click');
                        });
                    });
                });
            };

            var save = function(element) {
                var task = $(element);
                var params = {
                    taskId: task.attr('id'),
                    state: task.parent().attr('data-state'),
                    storyId: task.parent().parent().parent().parent().attr('data-storyid'),
                    body: task.find('div.body').html(),
                    color: task.find('div.body').css('background-color'),
                    owner: task.find('select.owner').val(),
                    estimation: task.find('input.estimation').val(),
                    done: task.find('input.done').val()
                };

                Scrumie.Sprint.refreshSummaryPoints();

                $.post(uri('Project', 'saveTask'), params, function(response) {
                    if(response) {
                        task.attr('id', response);
                    } else {
                        alert('Problem with saving data');
                    }
                });
            };

            var del = function(element) {
                var task = $(element);
                var confirm = $(task).find('.confirm_delete').addClass('show');

                var yes = $(confirm).find('button.yes');
                var cancel = $(confirm).find('button.cancel');

                yes.unbind('click');
                cancel.unbind('click');

                yes.click(function() {
                    if(task.attr('id')) {
                        $.post(uri('Project', 'deleteTask'), {id: task.attr('id')}, function(response) {
                            if(response === true) {
                                task.remove();
                            } else {
                                alert(respone.error);
                            }
                        });
                    } else {
                        task.remove();
                    }
                });

                cancel.click(function() {
                    confirm.removeClass('show');
                });
            };

            var changeProgress = function(event, el) {
                if(event.keyCode == 38) {
                    $(el).val(parseInt($(el).val(),10) + 1);
                    $(el).change();
                } else if (event.keyCode==40) {
                    $(el).val(parseInt($(el).val(),10) - 1);
                    $(el).change();
                }
            };

            return {
                add: add,
                edit: edit,
                save: save,
                del: del,
                changeProgress: changeProgress
            };
        }();

        var logout = function() {
            location.href = uri('User', 'logout');
        };

        var login = function() {
            location.href = uri('User', 'logout');
        };

        var droppable = function() {
            $(".droppable").droppable({
                drop: function(event,ui) {
                    ui.helper.appendTo(this);
                    $(this).removeClass('over');
                    Story.recalculate($(this).parent());
                },
                over: function(event, ui) {
                    $(this).addClass('over');
                },
                out: function(event, ui) {
                    $(this).removeClass('over');
                }
            });
        };

        var draggable = function() {
            $(".draggable").draggable({
                stop: function(event, ui) {
                    $(this).css('top', 0);
                    $(this).css('left', 0);
                    Scrumie.Task.save(this);
                }
            });
        };


        return {
            Project: Project,
            Sprint: Sprint,
            Story: Story,
            Task: Task,
            login: login,
            logout: logout,
            droppable: droppable,
            draggable: draggable,
            uri: uri
        };
    }();

    $(document).ready(function() {

        $.ajax({
            cache: false
        });

        $( "#tabs" ).tabs();

        var tabIndex = parseInt($.getUrlVar('tab'),10);
        if(tabIndex) {
            $( "#tabs" ).tabs({ selected: parseInt(tabIndex,10) });
        }
        else if($.getUrlVar('sprint')) {
            $( "#tabs" ).tabs({ selected: 1 });
        }

        setInterval( function() {$.get('?controller=Index&action=keepAlive'); }, 100000);

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
                    alert(data.error);
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
            dialogClass: 'loginDialog'
        });

        Scrumie.droppable();
        Scrumie.draggable();

    });

    window.Scrumie = Scrumie;
})(jQuery);

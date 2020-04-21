define(
    ['jquery', 'core/ajax', 'core/notification', 'core/str', 'core/url', 'core/modal_factory', 'core/modal_events'],
    function($, AJAX, NOTIFICATION, STR, URL, ModalFactory, ModalEvents) {
    return {
        enrolmentrole: function(roleid) {
            console.log('block_edureportbook/main.enrolmentrole(roleid)', roleid);
            var MAIN = this;
            var sel = $('#id_roletoassign');
            if (sel.length === 0) {
                setTimeout(function() { MAIN.enrolmentrole(roleid); }, 100);
            } else {
                $(sel).val(roleid);
            }
        },
        participantsform: function(courseid) {
            console.log('block_edureportbook/partipantsform(courseid)', courseid);
            MAIN = this;
            AJAX.call([{
                methodname: 'block_edureportbook_participantsform',
                args: { courseid: courseid },
                done: function(result) {
                    console.log('Got modal');
                    // Remove any previously created forms.
                    $('#block_edureportbook_participantsform').remove();
                    //console.log(result);
                    ModalFactory.create({
                        //title: 'create issue',
                        type: ModalFactory.types.SAVE_CANCEL,
                        body: result,
                        large: 1,
                        //footer: 'footer',
                    }).done(function(modal) {
                        console.log('Created modal');
                        MAIN.modal = modal;
                        var body = $(MAIN.modal.body);
                        MAIN.modal.setLarge();
                        MAIN.modal.getRoot().on(ModalEvents.save, function(e) {
                            // Stop the default save button behaviour which is to close the modal.
                            MAIN.participantsstore(MAIN.modal);
                            e.preventDefault();
                            // Do your form validation here.
                        });
                        MAIN.modal.show();
                    });
                },
                fail: NOTIFICATION.exception
            }]);
        },
        participantsstore: function(modal){
            MAIN = this;
            console.log('block_edureportbook/partipantsstore(modal)', modal);
            var form = $(modal.body).find('form');
            //console.log('Found form', form);
            var arr = form.serializeArray();
            var data = {};
            //console.log('Serialized form', arr);
            for (var a = 0; a < arr.length; a++) {
                if (arr[a].name.indexOf('[') > 0) {
                    var xname = arr[a].name.split('[');

                    var xid = xname[1].split(']');
                    var id = xid[0];
                    var name = xname[0];
                    if (typeof data[name] === 'undefined') {
                        data[name] = [];
                    }

                    if (id != '') {
                        data[name][id] = arr[a].value;
                    } else {
                        data[name][data[name].length] = arr[a].value;
                    }
                } else if(arr[a].value != '_qf__force_multiselect_submission') {
                    data[arr[a].name] = arr[a].value;
                }
            }
            //console.log('Sending data', data);
            AJAX.call([{
                methodname: 'block_edureportbook_participantsstore',
                args: { data: JSON.stringify(data) },
                done: function(result) {
                    console.log('Got result', result);
                    if (result == 1) {
                        //alert('yeha');
                        MAIN.modal.hide();
                    } else {
                        alert('Sorry, there was an error!');
                    }
                },
                fail: NOTIFICATION.exception
            }]);
        },
        removeblock: function(courseid) {
            console.log('block_edureportbook/removeblock(courseid)', courseid);
            MAIN = this;
            AJAX.call([{
                methodname: 'block_edureportbook_removeblock',
                args: { courseid: courseid },
                done: function(result) {
                    console.log('Got result', result);
                    if (result == 1) {
                        top.location.href = URL.fileUrl("/course/view.php", "") + '?id=' + courseid;
                    } else {
                        alert('Sorry, there was an error!');
                    }
                },
                fail: NOTIFICATION.exception
            }]);
        },
        separateform: function(courseid) {
            console.log('block_edureportbook/separateform(courseid)', courseid);
            MAIN = this;
            AJAX.call([{
                methodname: 'block_edureportbook_separateform',
                args: { courseid: courseid },
                done: function(result) {
                    console.log('Got modal');
                    // Remove any previously created forms.
                    $('#block_edureportbook_separateform').remove();
                    //console.log(result);
                    ModalFactory.create({
                        //title: 'create issue',
                        type: ModalFactory.types.SAVE_CANCEL,
                        body: result,
                        large: 1,
                        //footer: 'footer',
                    }).done(function(modal) {
                        console.log('Created modal');
                        MAIN.modal = modal;
                        var body = $(MAIN.modal.body);
                        MAIN.modal.setLarge();
                        MAIN.modal.getRoot().on(ModalEvents.save, function(e) {
                            // Stop the default save button behaviour which is to close the modal.
                            MAIN.separatestore(MAIN.modal);
                            e.preventDefault();
                            // Do your form validation here.
                        });
                        MAIN.modal.show();
                    });
                },
                fail: NOTIFICATION.exception
            }]);
        },
        separatestore: function(modal){
            MAIN = this;
            console.log('block_edureportbook/separatestore(modal)', modal);
            var form = $(modal.body).find('form');
            var arr = form.serializeArray();
            var data = {};
            for (var a = 0; a < arr.length; a++) {
                if (arr[a].name.indexOf('[') > 0) {
                    var xname = arr[a].name.split('[');

                    var xid = xname[1].split(']');
                    var id = xid[0];
                    var name = xname[0];
                    if (typeof data[name] === 'undefined') {
                        data[name] = [];
                    }

                    if (id != '') {
                        data[name][id] = arr[a].value;
                    } else {
                        data[name][data[name].length] = arr[a].value;
                    }
                } else {
                    data[arr[a].name] = arr[a].value;
                }
            }
            console.log('Sending data', data);
            AJAX.call([{
                methodname: 'block_edureportbook_separatestore',
                args: { data: JSON.stringify(data) },
                done: function(result) {
                    //console.log('Got result', result);
                    modal.hide();
                    STR.get_strings([
                            {'key' : 'separate:error', component: 'block_edureportbook' },
                            {'key' : 'separate:success', component: 'block_edureportbook' },
                            {'key' : 'separate', component: 'block_edureportbook' },
                        ]).done(function(s) {
                            NOTIFICATION.alert(s[2], (result == 1) ? s[1] : s [0]);
                        }
                    ).fail(NOTIFICATION.exception);
                },
                fail: NOTIFICATION.exception
            }]);
        },
    };
});

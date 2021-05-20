define(
    ['jquery', 'core/ajax', 'core/notification', 'core/str', 'core/url', 'core/modal_factory', 'core/modal_events'],
    function($, AJAX, NOTIFICATION, STR, URL, ModalFactory, ModalEvents) {
    return {
        relation_load: function(uniqid, courseid, studentid) {
            console.log('block_edureportbook/relation_load(uniqid, courseid, studentid)', uniqid, courseid, studentid);
            $('#students-' + uniqid + ' .name-label, #legalguardians-' + uniqid + ' .name-label').removeClass('selected');
            $('#student-' + uniqid + '-' + studentid).addClass('selected');
            $('#legalguardians-' + uniqid + ' .name-label').removeClass('pending');

            AJAX.call([{
                methodname: 'block_edureportbook_relations',
                args: { courseid: courseid, studentid: studentid },
                done: function(result) {
                    result = result.split(',')
                    console.log('Got result', result);
                    // Check if this student is still selected.
                    if ($('#student-' + uniqid + '-' + studentid).hasClass('selected')) {
                        for (a = 0; a < result.length; a++) {
                            $('#legalguardian-' + uniqid + '-' + result[a]).addClass('selected');
                        }
                    }
                },
                fail: NOTIFICATION.exception
            }]);
        },
        relation_set: function(uniqid, courseid, parentid) {
            console.log('block_edureportbook/relation_set(uniqid, courseid, parentid)', uniqid, courseid, parentid);

            if ($('#students-' + uniqid + ' .name-label.selected').length == 0) {
                // @todo show nice warning.
                return;
            }
            var studentid = $('#students-' + uniqid + ' .name-label.selected').attr('data-id');
            $('#legalguardian-' + uniqid + '-' + parentid).addClass('pending');
            var to = $('#legalguardian-' + uniqid + '-' + parentid).hasClass('selected') ? 0 : 1;

            AJAX.call([{
                methodname: 'block_edureportbook_relation',
                args: { courseid: courseid, studentid: studentid, parentid: parentid, to: to },
                done: function(confirmed_to) {
                    console.log('Got result', confirmed_to);
                    // Check if this student is still selected.
                    if ($('#student-' + uniqid + '-' + studentid).hasClass('selected')) {
                        $('#legalguardian-' + uniqid + '-' + parentid).removeClass('pending');
                        if (confirmed_to == 1) {
                            $('#legalguardian-' + uniqid + '-' + parentid).addClass('selected');
                        } else {
                            $('#legalguardian-' + uniqid + '-' + parentid).removeClass('selected');
                        }
                    }
                },
                fail: NOTIFICATION.exception
            }]);
        },
    };
});

var ajaxing = false;
var perData = {};
perData.skillSelected = [];

// init handlebars
if ($('#skill-template')[0]){
    var skillTemplate = Handlebars.compile($("#skill-template").html());
}

$(document).ready(function() {
    // init selected skill
    $('.skill-id').each(function() {
        var rowIdArr = $(this).attr('id').split('-');
        var rowId = rowIdArr[rowIdArr.length-1];
        perData.skillSelected.push(parseInt($('#row-skill-'+rowId).find('.skill').val()));
    });

    $('.submit-test-btn').click(function () {
        var validateResult = $('#add-test-form').parsley().validate();
        if (validateResult) {
            $('#add-test-form')[0].submit();
        }
    });

    $('.set-score-btn').click(function () {
        var validateResult = $('#set-score-form').parsley().validate();
        console.log(validateResult);
        // if (validateResult) {
        //     $('#set-score-form')[0].submit();
        // }
    });

    $('.select-lesson-from').change(function() {
        $('.select-lesson-to').attr('min', $(this).val());
    });

    $('.select-lesson-to').change(function() {
        $('.select-lesson-from').attr('max', $(this).val());
    });

    $('#jclass-id').change(function() {
        var classId = this.value;
        if (ajaxing) {
            // still requesting
            return;
        }
        ajaxing = true;
        $('#add-test-overlay').removeClass('hidden');
        
        $.ajax({
            type: 'GET',
            url: '/tvms/jtests/getStudents',
            data: {
                id: classId,
                testId: $('input[name="id"]').val()
            },
            success: function(resp) {
                if (resp === undefined || resp.length == 0) {
                    return;
                }

                // clear id field
                $('#student-test-container').html('');

                if (resp.status === "unchanged") {
                    // init id when the attendeces unchanged
                    var source = $("#student-test-template").html();
                    var template = Handlebars.compile(source);
                    var html = template(resp.ids);
                    $('#student-test-container').html(html);
                    $('input[name="changed"]').val('false');
                } else {
                    $('input[name="changed"]').val('true');
                }
                var source = $("#student-template").html();
                var template = Handlebars.compile(source);
                var html = template(resp.data);
                $('#student-container').html(html);
            },
            complete: function() {
                ajaxing = false;
                $('#add-test-overlay').addClass('hidden');
            }
        });
    });
});

function showAddSkillModal() {
    // reset form
    $('#modal-skill').val(null).trigger('change');
    $('#modal-teacher').val(null).trigger('change');
    $('#add-skill-form').parsley().reset();

    $('#add-skill-btn').remove();
    $('<button type="button" class="btn btn-success" id="add-skill-btn" onclick="addSkill()">Submit</button>').insertBefore('#close-add-skill-modal-btn');

    $('#add-skill-modal').modal('toggle');
}

function showEditSkillModal(ele) {
    $('#modal-skill').val($(ele).closest('.row-skill').find('.skill').val()).trigger('change');
    $('#modal-teacher').val($(ele).closest('.row-skill').find('.teacher').val()).trigger('change');

    var rowIdArr = $(ele).closest('.row-skill').attr('id').split('-');
    var rowId = rowIdArr[rowIdArr.length-1];
    var initSkill = $('#modal-skill').val();

    $('#add-skill-btn').remove();
    $('<button type="button" class="btn btn-success" id="add-skill-btn" onclick="editSkill('+rowId+', '+initSkill+')">Submit</button>').insertBefore('#close-add-skill-modal-btn');

    $('#add-skill-modal').modal('toggle');
}

function createSkillTemplate(counter) {
    var html = skillTemplate({
        'row': counter + 1,
        'counter': counter,

        'skillText': $('#modal-skill option:selected').html(),
        'teacherText': $('#modal-teacher option:selected').html(),
    });
    return html;
}

function addSkill() {
    //validate form
    var skillId = parseInt($('#modal-skill').val());
    if (perData.skillSelected.indexOf(skillId) >= 0) {
        alert('You have already selected this skill. Please choose another skill!');
        return;
    }

    var validateResult = $('#add-skill-form').parsley().validate();
    if (validateResult) {
        var skill_html = createSkillTemplate(perData.skillSelected.length);
        $('#skill-container').append(skill_html);

        // set value for select box
        $('select[name="jtest_contents['+perData.skillSelected.length+'][skill]"]').val($('#modal-skill').val());
        $('select[name="jtest_contents['+perData.skillSelected.length+'][user_id]"]').val($('#modal-teacher').val());

        $('#add-skill-modal').modal('toggle');
        perData.skillSelected.push(parseInt($('#modal-skill').val()));
    }
}

function editSkill(rowId, initSkill) {
    var skillId = parseInt($('#modal-skill').val());
    if (perData.skillSelected.indexOf(skillId) >= 0) {
        alert('You have already selected this skill. Please choose another skill!');
        return;
    }

    //validate form
    var validateResult = $('#add-skill-form').parsley().validate();
    if (validateResult) {
        // update selected data
        var orgIndex = perData.skillSelected.indexOf(initSkill);
        perData.skillSelected[orgIndex] = skillId;

        // change text
        $('#row-skill-'+rowId).find('.skill-name').html($('#modal-skill option:selected').html());
        $('#row-skill-'+rowId).find('.teacher-name').html($('#modal-teacher option:selected').html());
        // set value for select box
        $('select[name="jtest_contents['+rowId+'][skill]"]').val($('#modal-skill').val());
        $('select[name="jtest_contents['+rowId+'][user_id]"]').val($('#modal-teacher').val());

        // close modal
        $('#add-skill-modal').modal('toggle');
    }
}

function deleteSkill(delEl, sendAjax) {
    if (sendAjax) {
        swal({
            title: 'Remove skill',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.value) {
                var rowIdArr = $(delEl).closest('.row-skill').attr('id').split('-');
                var rowId = rowIdArr[rowIdArr.length-1];

                $.ajax({
                    type: 'POST',
                    url: '/tvms/jtests/deleteSkill',
                    data: {
                        'id': $('#skill-id-'+rowId).find('input').val(),
                    },
                    success: function(resp){
                        swal({
                            title: resp.alert.title,
                            text: resp.alert.message,
                            type: resp.alert.type
                        })
                        if (resp.status == 'success') {
                            deleteRow(delEl, rowId);
                        }
                    }
                });
            }
        });
    } else {
        deleteRow(delEl);
    }
}

function deleteRow(delEl, hiddenId) {
    // remove DOM
    $(delEl).closest('.row-skill').remove();

    if (hiddenId != null) {
        // case: remove record exists in database
        $('#skill-id-'+hiddenId).remove();
    } 
    var skillId = parseInt($(delEl).closest('.row-skill').find('.skill').val());
    // remove in selected array
    perData.skillSelected.splice(perData.skillSelected.indexOf(skillId), 1);

    var trows = $('#skill-container > tr');
    var idField = $('.skill-id').find('input');
    var selectField = $('#skill-container').find('select');
    var sttField = $('#skill-container').find('.stt-col');

    for (var i = 0; i < sttField.length; i++) {
        sttField[i].innerText = i + 1;
        trows[i].id = 'row-skill-' + i;

        if (hiddenId != null) {
            $('.skill-id')[i].id = 'skill-id-' + i;
            idField[i].name = 'jtest_contents[' + i + '][id]';
        }

        classArr = selectField[i].className.split(' ');
        selectField[i].name = 'jtest_contents[' + Math.floor(i/2) + '][' + classArr[classArr.length-1] + ']';
    }

    for (var i = 0; i < selectField.length; i++) {
        classArr = selectField[i].className.split(' ');
        selectField[i].name = 'jtest_contents[' + Math.floor(i/2) + '][' + classArr[classArr.length-1] + ']';
    }
}
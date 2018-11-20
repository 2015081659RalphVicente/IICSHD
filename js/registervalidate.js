/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {

    $.validator.setDefaults({
        errorClass: 'form-text',
        highlight: function (element) {
            $(element)
                    .closest('.form-control')
                    .addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element)
                    .closest('.form-control')
                    .removeClass('is-invalid')
                    .addClass('is-valid');
        },
        errorPlacement: function (error, element) {
            if (element.prop('type') === 'checkbox') {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    jQuery.validator.addMethod("ustedu", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(ust)\.edu\.ph*$/.test(value);
    }, "Please use your <em>ust.edu.ph</em> email address.");

    jQuery.validator.addMethod("passwordx", function (value, element) {
        return this.optional(element) || /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/.test(value);
    }, "Your password must be atleast 8 characters long and must be a combination of uppercase letters, lowercase letters and numbers.");

    jQuery.validator.addMethod("num", function (value, element) {
        return this.optional(element) || /^[0-9]{10,10}$/.test(value);
    }, "Input must contain numbers only and should have 10 digits.");

    jQuery.validator.addMethod("fname", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\ ]*$/.test(value);
    }, "Input must contain letters only.");

    jQuery.validator.addMethod("mname", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\. {2,4}]*$/.test(value);
    }, "Input must contain a letter and a period (.)");

    jQuery.validator.addMethod("lname", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\ ]*$/.test(value);
    }, "Input must contain letters only.");




    $("#student-register").validate({
        rules: {
            studnum: {
                required: true,
                num: true
            },
            studfname: {
                required: true,
                fname: true
            },
            studmname: {
                required: true,
                mname: true
            },
            studlname: {
                required: true,
                lname: true
            },
            studsection: {
                required: true
            },
            studemail: {
                required: true,
                email: true,
                ustedu: true
            },
            studpass: {
                required: true,
                passwordx: true
            },
            studconfpass: {
                required: true,
                equalTo: "#studpass"
            },
            studsecq: {
                required: true
            },
            studsecans: {
                required: true
            }
        },

        messages: {
            studnum: {
                required: 'This field is required.'
            },
            studfname: {
                required: 'This field is required.'
            },
            studmname: {
                required: 'This field is required.'
            },
            studlname: {
                required: 'This field is required.'
            },
            studsection: {
                required: 'This field is required.'
            },
            studemail: {
                required: 'This field is required.',
                email: 'Please use your <em>ust.edu.ph</em> email address.'
            },
            studpass: {
                required: 'This field is required.'
            },
            studconfpass: {
                required: 'This field is required.',
                equalTo: 'Password does not match the confirm password.'
            },
            studsecq: {
                required: 'This field is required.'
            },
            studsecans: {
                required: 'This field is required.'
            }
        }

    });

    $("#faculty-register").validate({
        rules: {
            empnum: {
                required: true,
                num: true
            },
            empfname: {
                required: true,
                fname: true
            },
            empmname: {
                required: true,
                mname: true
            },
            emplname: {
                required: true,
                lname: true
            },
            empemail: {
                required: true,
                email: true,
                ustedu: true
            },
            emppass: {
                required: true,
                passwordx: true
            },
            empconfpass: {
                required: true,
                equalTo: "#emppass"
            },
            empsecq: {
                required: true
            },
            empsecans: {
                required: true
            }
        },

        messages: {
            empnum: {
                required: 'This field is required.'
            },
            empfname: {
                required: 'This field is required.'
            },
            empmname: {
                required: 'This field is required.'
            },
            emplname: {
                required: 'This field is required.'
            },
            empemail: {
                required: 'This field is required.',
                email: 'Please use your <em>ust.edu.ph</em> email address.'
            },
            emppass: {
                required: 'This field is required.'
            },
            empconfpass: {
                required: 'This field is required.',
                equalTo: 'Password does not match the confirm password.'
            },
            empsecq: {
                required: 'This field is required.'
            },
            empsecans: {
                required: 'This field is required.'
            }
        }

    });
});



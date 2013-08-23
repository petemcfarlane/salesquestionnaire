$(document).ready(function() {

	/* ------------------------
	 * PJAX
	 * -----------------------*/

	$(document).on('click', '#content a[href!=#]', function(e) {
	    if ($(this).hasClass('contact_info_link')) return
        $.pjax.click(e, '#content')
	})
	
	$(document).on('submit', '#content form', function(e) {
	    $.pjax.submit(e, '#content')
	})

	/* ------------------------
	 * Load contacts
	 * -----------------------*/

	var contacts, currentContacts = []
    $.get( OC.filePath('contacts', 'ajax', 'contact/list.php'), null, function(json) {
        if (json && json.status == 'success') contacts = json.data.contacts
    }, "json")

	$('.meetingWith').each(function(i) { currentContacts.push( parseInt($('.meetingWith').eq(i).val()) ); })

	$('#content').on('keyup', '#meetingWithMarker', function(e) {
		var input = $(this)
		if ( input.val().length < 2 || [13, 40, 38, 9].indexOf(e.which) != -1 ) return // return, up, down, tab
        listSuggestions(input, currentContacts)
		$(input).parent().find('.suggestions li').on('mousedown', function() { addMeetingWith(); })
		$(input).focusout(function() { clearSuggestions(); $('#meetingWithMarker').val(''); })
	})

    $('#content').on('keydown','#meetingWithMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            e.preventDefault(); addMeetingWith();
        }
    })

    function addMeetingWith() {
        $('#meetingWithMarker').before('<div class="row collapse">'
            + '<input type="hidden" name="meetingWith[]" value="' + $('.suggestions li.selected').data('id') + '" />'
            + '<div class="small-2 large-1 columns"><a class="small-12 prefix removePerson button secondary" data-id="' + $('.suggestions li.selected').data('id') + '">Remove</a></div>'
            + '<div class="small-10 large-11 columns"><input type="text" value="' + $('.suggestions li.selected').text() + '" readonly /></div>'
            + '</div>').val('').focus()
        currentContacts.push( $('.suggestions li.selected').data('id') )
        clearSuggestions()
     }

	$('#content').on('click', '.removePerson', function(e){
	    currentContacts.splice( currentContacts.indexOf($(this).data('id')), 1);
	    $(this).parent().parent().remove()
	})

    $('#content').on('keyup', '#technicalAuthorityMarker', function(e) {
        var input = $(this)
        if ( [13, 40, 38, 9].indexOf(e.which) != -1 ) return // return, up, down, tab
        $('#technicalAuthority').val('')
        if (input.val().length < 2) return
        listSuggestions(input)
        $(input).parent().find('.suggestions li').on('mousedown', function() { addTechAuth() })
        $(input).focusout(function() { clearSuggestions(); })
    })

    $('#content').on('keydown','#technicalAuthorityMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            addTechAuth(); if (e.which == 13) e.preventDefault();
        }
    })
    
    function addTechAuth() {
        $('#technicalAuthority').val( $('.suggestions li.selected').data('id') )
        $('#technicalAuthorityMarker').val( $('.suggestions li.selected').text() )
        clearSuggestions()
    }

    $('#content').on('keyup', '#commercialAuthorityMarker', function(e) {
        var input = $(this)
        if ( [13, 40, 38, 9].indexOf(e.which) != -1 ) return // return, up, down, tab
        $('#commercialAuthority').val('')
        if (input.val().length < 2) return
        listSuggestions(input)
        $(input).parent().find('.suggestions li').on('mousedown', function() { addComAuth() })
        $(input).focusout(function() { clearSuggestions(); })
    })

    $('#content').on('keydown','#commercialAuthorityMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            addComAuth(); if (e.which == 13) e.preventDefault();
        }
    })
    
    function addComAuth() {
        $('#commercialAuthority').val( $('.suggestions li.selected').data('id') )
        $('#commercialAuthorityMarker').val( $('.suggestions li.selected').text() )
        clearSuggestions()
    }

    function listSuggestions(input, exclude) {
        exclude = typeof exclude !== 'undefined' ? exclude : []
        clearSuggestions()
        if (!contacts) {
            $.get( OC.filePath('contacts', 'ajax', 'contact/list.php'), null, function(json) {
                if (json && json.status == 'success') contacts = json.data.contacts
            }, "json")
            return
        }
        var count = 0
        $.each(contacts, function(i, contact) {
            if (count > 4) return false
            var name = contact.data.FN[0].value
            var id   = contact.id
            if (name.toLowerCase().indexOf(input.val().toLowerCase()) != -1 && exclude.indexOf(parseInt(id)) == -1 ) {
                $(input).next().removeClass('hide').append('<li data-id=' + id + '>' + name + '</li>')
                count++
            }
            $('.suggestions li:first-child').addClass('selected')
        })
    }

    function clearSuggestions() {
        $('.suggestions').addClass('hide').empty()
    }

    $('#content').on('hover', '.suggestions li', function() {
        $('.suggestions li.selected').removeClass('selected')
        $(this).addClass('selected')
    })

    $('#content').on('keydown','.person', function(e) {
        if ([40,38].indexOf(e.which) != -1) {
            e.preventDefault(); var old = $('.suggestions li.selected')
            if (e.which == 40 && old.next().length > 0 ) old.removeClass('selected').next().addClass('selected') // down key
            if (e.which == 38 && old.prev().length > 0 ) old.removeClass('selected').prev().addClass('selected') // up key
        }
    })


    /* ------------------------
     * Load contact details
     * -----------------------*/

    $('#content').on('click', '.contact_info_link', function(e) {
        e.preventDefault()
        $('.contact-info-container').remove()
        $.post( OC.filePath('salesquestionnaire', 'ajax', 'contactinfo.php'), 'contactId='+$(this).data('id'), function(data) {
            console.log(data)
            var contactInfo = '<div class="contact-info-container row">'
                + '<div class="contact-info large-12 columns">'
                    + '<div class="row">'
                        + '<div class="columns small-3 center">'
                            + '<img src="/index.php/apps/contacts/photo.php?id=' + data['id'] + '" />'
                        + '</div>'
                        + '<div class="columns small-9">'
                            + '<h3>' + data['details']['FN'][0]['value'] + '</h3>'
                            + '<p>' + data['details']['TITLE'][0]['value'] + '</p>'
                            + '<p>' + data['details']['ORG'][0]['value'] + '</p>'
                        + '</div>'
                    + '</div>'
                    if (data['details']['EMAIL'].length > 0) {
                        for(var i=0;i<data['details']['EMAIL'].length;i++){
                            contactInfo += '<div class="row"><div class="columns small-3">'+data['details']['EMAIL'][i]['parameters']['TYPE'] +'</div><div class="columns small-9">'+ data['details']['EMAIL'][i]['value'] +'</div></div>'
                        }
                    }
                    contactInfo += '<div class="row"><div class="columns small-3">Mobile</div><div class="columns small-9">'+ data['details']['TEL'][0]['value'] +'</div></div>'
                    + '<div class="row"><div class="columns small-3">Homepage</div><div class="columns small-9">'+ data['details']['URL'][0]['value'] +'</div></div>'
                    + '<div class="row"><div class="columns small-3">Work</div><div class="columns small-9">'+ data['details']['ADR'][0]['value'] +'</div></div>'
                    + '<div class="row"><div class="columns small-3">Birthday</div><div class="columns small-9">'+ data['details']['BDAY'][0]['value'] +'</div></div>'
                    + '<div class="row"><div class="columns small-3">Notes</div><div class="columns small-9">'+ data['details']['NOTE'][0]['value'] +'</div></div>'
                + '</div>'
            + '</div>'
            $('.contact_info_link[data-id='+data['id']+']').after(contactInfo)
        }, "json")
    })

})

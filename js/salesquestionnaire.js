$(document).ready(function() {

	/* ------------------------
	 * PJAX
	 * -----------------------*/

	$(document).on('click', '#content a[href!=#]', function(e) {
	    if ($(this).data('skip-pjax')) return
        $.pjax.click(e, '#content');
	});
	
	$(document).on('submit', '#content form', function(e) {
	    $.pjax.submit(e, '#content');
	});

	/* ------------------------
	 * Load contacts
	 * -----------------------*/

	var contacts, currentContacts = [];
    $.get( OC.filePath('contacts', 'ajax', 'contact/list.php'), null, function(json) {
        if (json && json.status == 'success') contacts = json.data.contacts;
    }, "json");

	$('.meetingWith').each(function(i) { currentContacts.push( parseInt($('.meetingWith').eq(i).val()) ); });

	$('#content').on('keyup', '#meetingWithMarker', function(e) {
		var input = $(this);
		if ( input.val().length < 2 || [13, 40, 38, 9].indexOf(e.which) != -1 ) return // return, up, down, tab
        listSuggestions(input, currentContacts);
		$(input).parent().find('.suggestions li').on('mousedown', function() { addMeetingWith(); });
		$(input).focusout(function() { clearSuggestions(); $('#meetingWithMarker').val(''); });
	});

    $('#content').on('keydown','#meetingWithMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            e.preventDefault(); addMeetingWith();
        }
    });

    function addMeetingWith() {
        $('#meetingWithMarker').before('<div class="row collapse">'
            + '<input type="hidden" name="meetingWith[]" value="' + $('.suggestions li.selected').data('id') + '" />'
            + '<div class="small-3 large-1 columns"><a class="small-12 prefix removePerson button secondary" data-id="' + $('.suggestions li.selected').data('id') + '">Remove</a></div>'
            + '<div class="small-9 large-11 columns"><input type="text" value="' + $('.suggestions li.selected').text() + '" readonly /></div>'
            + '</div>').val('').focus();
        currentContacts.push( $('.suggestions li.selected').data('id') );
        clearSuggestions();
     }

	$('#content').on('click', '.removePerson', function(e){
	    currentContacts.splice( currentContacts.indexOf($(this).data('id')), 1);
	    $(this).parent().parent().remove();
	});

    $('#content').on('keyup', '#technicalAuthorityMarker', function(e) {
        var input = $(this);
        if ( [13, 40, 38, 9].indexOf(e.which) != -1 ) return // return, up, down, tab
        $('#technicalAuthority').val('');
        if (input.val().length < 2) return
        listSuggestions(input);
        $(input).parent().find('.suggestions li').on('mousedown', function() { addTechAuth(); });
        $(input).focusout(function() { clearSuggestions(); });
    });

    $('#content').on('keydown','#technicalAuthorityMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            addTechAuth(); if (e.which == 13) e.preventDefault();
        }
    });
    
    function addTechAuth() {
        $('#technicalAuthority').val( $('.suggestions li.selected').data('id') );
        $('#technicalAuthorityMarker').val( $('.suggestions li.selected').text() );
        clearSuggestions();
    }

    $('#content').on('keyup', '#commercialAuthorityMarker', function(e) {
        var input = $(this);
        if ( [13, 40, 38, 9].indexOf(e.which) != -1 ) return; // return, up, down, tab
        $('#commercialAuthority').val('');
        if (input.val().length < 2) return
        listSuggestions(input);
        $(input).parent().find('.suggestions li').on('mousedown', function() { addComAuth(); });
        $(input).focusout(function() { clearSuggestions(); });
    });

    $('#content').on('keydown','#commercialAuthorityMarker', function(e) {
        if ( (e.which == 13 || e.which == 9) && $('.suggestions li.selected').length > 0 ) { // return or tab
            addComAuth(); if (e.which == 13) e.preventDefault();
        }
    });
    
    function addComAuth() {
        $('#commercialAuthority').val( $('.suggestions li.selected').data('id') );
        $('#commercialAuthorityMarker').val( $('.suggestions li.selected').text() );
        clearSuggestions();
    }

    function listSuggestions(input, exclude) {
        exclude = typeof exclude !== 'undefined' ? exclude : [];
        clearSuggestions();
        if (!contacts) {
            $.get( OC.filePath('contacts', 'ajax', 'contact/list.php'), null, function(json) {
                if (json && json.status == 'success') contacts = json.data.contacts;
            }, "json");
            return;
        }
        var count = 0;
        $.each(contacts, function(i, contact) {
            if (count > 4) return false;
            var name = contact.data.FN[0].value;
            var id   = contact.id;
            if (name.toLowerCase().indexOf(input.val().toLowerCase()) != -1 && exclude.indexOf(parseInt(id)) == -1 ) {
                $(input).next().removeClass('hide').append('<li data-id=' + id + '>' + name + '</li>');
                count++;
            }
            $('.suggestions li:first-child').addClass('selected');
        });
    }

    function clearSuggestions() {
        $('.suggestions').addClass('hide').empty();
    }

    $('#content').on('hover', '.suggestions li', function() {
        $('.suggestions li.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    $('#content').on('keydown','.person', function(e) {
        if ([40,38].indexOf(e.which) != -1) {
            e.preventDefault(); var old = $('.suggestions li.selected');
            if (e.which == 40 && old.next().length > 0 ) old.removeClass('selected').next().addClass('selected'); // down key
            if (e.which == 38 && old.prev().length > 0 ) old.removeClass('selected').prev().addClass('selected'); // up key
        }
    });


    /* ------------------------
     * Load contact details
     * -----------------------*/

    $('#content').on('click', '.contact_info_link', function(e) {
        var contact = $(this);
        e.preventDefault();
        $('.contact-info-container').remove();
        $.post( OC.filePath('salesquestionnaire', 'ajax', 'contactinfo.php'), 'contactId='+contact.data('id'), function(data) {
            var details = data['details'];
            var contactInfo = '<div class="contact-info-container row">'
                + '<div class="contact-info large-12 columns">'
                    + '<div class="row">'
                        + '<div class="columns small-3 center">'
                            + '<img src="/index.php/apps/contacts/photo.php?id=' + data['id'] + '" />'
                        + '</div>'
                        + '<div class="columns small-9">';
                            if (details['FN']) {for(i=0;i<details['FN'].length;i++){
                                contactInfo += '<h3>' + details['FN'][i]['value'] + '</h3>';
                            }}
                            if (details['TITLE']) {for(i=0;i<details['TITLE'].length;i++){
                                contactInfo += '<h4>' + details['TITLE'][i]['value'] + '</h4>';
                            }}
                            if (details['ORG']) {for(i=0;i<details['ORG'].length;i++){
                                contactInfo += '<h4>' + details['ORG'][i]['value'] + '</h4>';
                            }}
                        contactInfo += '</div>'
                    + '</div>';
                    if (details['EMAIL']) {for(i=0;i<details['EMAIL'].length;i++){
                        contactInfo += '<div class="row"><div class="columns small-3 label">'+ capitalize(details['EMAIL'][i]['parameters']['TYPE'][0]) +'</div><div class="columns small-9 value"><a href="mailto:'+details['EMAIL'][i]['value']+'">'+ details['EMAIL'][i]['value'] +'</a></div></div>';
                    }}
                    if (details['TEL']) {for(i=0;i<details['TEL'].length;i++){
                        contactInfo += '<div class="row"><div class="columns small-3 label">'+ (details['TEL'][i]['parameters']['TYPE'] ? capitalize(details['TEL'][i]['parameters']['TYPE'][0]) : 'Phone') + '</div><div class="columns small-9 value">'+ details['TEL'][0]['value'] +'</div></div>';
                    }}
                    if (details['URL']) {for(i=0;i<details['URL'].length;i++){
                        contactInfo += '<div class="row"><div class="columns small-3 label">Homepage</div><div class="columns small-9 value"><a href="'+ details['URL'][i]['value'] +'" target="_blank">'+ details['URL'][i]['value'] +'</a></div></div>';
                    }}
                    if (details['ADR']) {for(i=0;i<details['ADR'].length;i++){
                        contactInfo += '<div class="row"><div class="columns small-3 label">'+ capitalize(details['ADR'][i]['parameters']['TYPE'][0]) +'</div><div class="columns small-9 value">';
                        console.log( details['ADR'][i]['value'].length);
                        for(var x=0;x<details['ADR'][i]['value'].length;x++) {
                            if (details['ADR'][i]['value'][x].length > 0) contactInfo += details['ADR'][i]['value'][x] + "<br />";
                        }
                        contactInfo += '</div></div>';
                    }}
                    if (details['BDAY']) {for(i=0;i<details['BDAY'].length;i++){
                        var date = details['BDAY'][i]['value'].split("-");
                        var bday = date[2] + "/" + date[1] + "/" + date[0];
                        contactInfo += '<div class="row"><div class="columns small-3 label">Birthday</div><div class="columns small-9 value">'+ bday +'</div></div>';
                    }}
                    if (details['NOTE']) {for(i=0;i<details['NOTE'].length;i++){
                        contactInfo += '<div class="row"><div class="columns small-3 label">Notes</div><div class="columns small-9 value">'+ details['NOTE'][i]['value'] +'</div></div>';
                    }}
                contactInfo += '<div class="row"><div class="columns large-12 center"><a class="close-contact">Close</a> | <a href="/index.php/apps/contacts#' + data.id + '" data-skip-pjax="true">Edit contact</a></div></div></div>'
            + '</div>';
            contact.after(contactInfo);
            
            $('.close-contact').click(function() {
                $('.contact-info-container').remove();
            });

        }, "json");
        
        $('html').click(function(e){
            if($(e.target).parents().index($('.contact-info-container')) == -1) {
                if($('.contact-info-container').length > 0 ) {
                    $('.contact-info-container').remove();
                }
            }
        });
        
    });

});

function capitalize (text) {
    return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}

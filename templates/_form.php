<div class="row">
	<div class="columns large-6"><label for="customer">Customer</label><input id="customer" type="text" name="customer" {% if response.code == 1 %}class="error"{% endif %} value="{{ questionnaire.customer }}" /></div>
	<div class="columns large-3"><label for="customerWebsite">Customer Website</label><input value="{{ questionnaire.customerWebsite }}" id="customerWebsite" type="url" name="customerWebsite" title="Must begin with http://" /></div>
	<div class="columns large-3"><label for="customerAddress">Customer Address</label><textarea id="customerAddress" name="customerAddress">{{ questionnaire.customerAddress }}</textarea></div>
</div>
<div class="row">
	<div class="columns large-6"><label for="projectName">Project Name</label><input value="{{ questionnaire.projectName }}" id="projectName" type="text" name="projectName" /></div>
	<div class="columns large-3"><label for="projectType">Project Type</label><input id="projectType" value="{{ questionnaire.projectType }}" type="text" name="projectType" /></div>
	<div class="columns large-3"><label for="platform">Platform</label><input id="platform" value="{{ questionnaire.platform }}" type="text" name="platform" /></div>
</div>
<div class="row">
	<div class="columns large-12">
		<label for="meetingWithMarker">Meeting With</label>
		<div class="relative">
			{% if questionnaire.meetingWith %}
				{% for person in questionnaire.meetingWith %}
					<div class="row collapse">
						<input type="hidden" name="meetingWith[]" value="{{ person.id }}" class="meetingWith" />
						<div class="small-3 large-1 columns"><a class="small-12 prefix removePerson button secondary" data-id="{{ person.id }}">Remove</a></div>
						<div class="small-9 large-11 columns"><input type="text" value="{{ person.fullname}}" readonly /></div>
					</div>
				{% endfor %}
			{% endif %}
			<input id="meetingWithMarker" type="text" class="person" autocomplete="off" placeholder="Start typing to look up contact" />
			<ul class="suggestions hide"></ul>
		</div>
	</div>
</div>
<div class="row">
	<div class="columns large-3"><label for="meetingDate">Meeting Date</label><input value="{{ questionnaire.meetingDate is empty ? "" : questionnaire.meetingDate|date("Y-m-d") }}" id="meetingDate" type="date" name="meetingDate" placeholder="dd/mm/yyyy" /></div>
	<div class="columns large-3"><label for="meetingLocation">Meeting Location</label><input value="{{ questionnaire.meetingLocation }}" id="meetingLocation" type="text" name="meetingLocation" /></div>
	<div class="columns large-3"><label for="representative">Representative</label><input id="representative" value="{{ questionnaire.representative }}" type="text" name="representative" /></div>
</div>
<div class="row">
	<div class="columns large-12">
		<label>Meeting Purpose</label>
	</div>
	<div class="columns large-12">
		<div class="columns small-6 large-3"><p><label class="normal"><input type="checkbox" name="meetingPurpose[]" value="Technical Discussion" {{ 'Technical Discussion' in questionnaire.meetingPurpose ? "checked " : ""}}/>Technical Discussion</label></p></div>
		<div class="columns small-6 large-3"><p><label class="normal"><input type="checkbox" name="meetingPurpose[]" value="Commercial Discussion" {{ 'Commercial Discussion' in questionnaire.meetingPurpose ? "checked " : ""}}/>Commercial Discussion</label></p></div>
		<div class="columns small-6 large-3"><p><label class="normal"><input type="checkbox" name="meetingPurpose[]" value="Present Quotation" {{ 'Present Quotation' in questionnaire.meetingPurpose ? "checked " : ""}}/>Present Quotation</label></p></div>
		<div class="columns small-6 large-3"><p><label class="normal"><input type="checkbox" name="meetingPurpose[]" value="Negotiate Order" {{ 'Negotiate Order' in questionnaire.meetingPurpose ? "checked " : ""}}/>Negotiate Order</label></p></div>
	</div>
	<br />
</div>
<div class="row">
	<div class="columns large-3"><label for="technicalAuthorityMarker">Technical Authority</label>
		<div class="relative">
			<input type="hidden" name="technicalAuthority" id="technicalAuthority" value="{{ questionnaire.technicalAuthority.id }}" />
			<input value="{{ questionnaire.technicalAuthority.fullname }}" id="technicalAuthorityMarker" type="text" autocomplete="off" placeholder="Start typing to look up a contact" class="person"/>
			<ul class="suggestions hide"></ul>
		</div>
	</div>
	<div class="columns large-3"><label for="commercialAuthorityMarker">Commercial Authority</label>
		<div class="relative">
			<input type="hidden" name="commercialAuthority" id="commercialAuthority" value="{{ questionnaire.commercialAuthority.id }}" />
			<input value="{{ questionnaire.commercialAuthority.fullname }}" id="commercialAuthorityMarker" type="text" autocomplete="off" placeholder="Start typing to look up a contact" class="person" />
			<ul class="suggestions hide"></ul>
		</div>
	</div>
</div>
<div class="row">
	<div class="columns large-6"><label for="technicalRequirements">Technical Requirements</label><textarea id="technicalRequirements" name="technicalRequirements">{{ questionnaire.technicalRequirements }}</textarea></div>
	<div class="columns large-6"><label for="commercialRequirements">Commercial Requirements</label><textarea id="commercialRequirements" name="commercialRequirements">{{ questionnaire.commercialRequirements }}</textarea></div>
</div>
<div class="row">
	<div class="columns large-6"><label for="otherRequirements">Other Requirements</label><textarea id="otherRequirements" name="otherRequirements">{{ questionnaire.otherRequirements }}</textarea></div>
	<div class="columns large-6"><label for="meetingNotes">Meeting Notes</label><textarea id="meetingNotes" name="meetingNotes">{{ questionnaire.meetingNotes }}</textarea></div>
</div>
<div class="row">
	<div class="columns large-3"><label for="purchaseDecision">Purchase Decision</label><input value="{{ questionnaire.purchaseDecision is empty ? "" : questionnaire.purchaseDecision|date("Y-m-d") }}" id="purchaseDecision" type="date" name="purchaseDecision" placeholder="dd/mm/yyyy"/></div>
	<div class="columns large-3"><label for="supplyEvaluation">Supply Evaluation</label><input value="{{ questionnaire.supplyEvaluation is empty ? "" : questionnaire.supplyEvaluation|date("Y-m-d") }}" id="supplyEvaluation" type="date" name="supplyEvaluation" placeholder="dd/mm/yyyy"/></div>
	<div class="columns large-3"><label for="optimizeBy">Optimize By</label><input value="{{ questionnaire.optimizeBy is empty ? "" : questionnaire.optimizeBy|date("Y-m-d") }}" id="optimizeBy" type="date" name="optimizeBy" placeholder="dd/mm/yyyy"/></div>
	<div class="columns large-3"><label for="manufactureDate">Manufacture Date</label><input value="{{ questionnaire.manufactureDate is empty ? "" : questionnaire.manufactureDate|date("Y-m-d") }}" id="manufactureDate" type="date" name="manufactureDate" placeholder="dd/mm/yyyy"/></div>
	<div class="columns large-3"><label for="retailDate">Retail Date</label><input value="{{ questionnaire.retailDate is empty ? "" : questionnaire.retailDate|date("Y-m-d") }}" id="retailDate" type="date" name="retailDate" placeholder="dd/mm/yyyy"/></div>
</div>
<div class="row">
	<div class="columns large-3"><label for="minimumOrder">Minimum Order</label><input value="{{ questionnaire.minimumOrder }}" id="minimumOrder" type="number" name="minimumOrder" /></div>
	<div class="columns large-3"><label for="rampYear1">Ramp up year 1</label><input value="{{ questionnaire.rampYear1 }}" id="rampYear1" type="number" name="rampYear1" /></div>
	<div class="columns large-3"><label for="rampYear2">Ramp up year 2</label><input value="{{ questionnaire.rampYear2 }}" id="rampYear2" type="number" name="rampYear2" /></div>
	<div class="columns large-3"><label for="rampYear3">Ramp up year 3</label><input value="{{ questionnaire.rampYear3 }}" id="rampYear3" type="number" name="rampYear3" /></div>
</div>
<div class="row">
	<div class="columns large-6"><label for="territories">Territories</label><input id="territories" value="{{ questionnaire.territories }}" type="text" name="territories" /></div>
	<div class="columns large-6"><label for="retailers">Retailers</label><input id="retailers" value="{{ questionnaire.retailers }}" type="text" name="retailers" /></div>
</div>
<div class="row">
	<div class="columns large-3"><label for="bom">B.O.M</label><input id="bom" value="{{ questionnaire.bom }}" type="text" name="bom" /></div>
	<div class="columns large-3"><label for="rrp">RRP</label><input id="rrp" value="{{ questionnaire.rrp }}" type="text" name="rrp" /></div>
	<div class="columns large-3"><label for="licenseFee">License Fee</label><input value="{{ questionnaire.licenseFee }}" id="licenseFee" type="text" name="licenseFee" /></div>
	<div class="columns large-3">
		<label for="budgeted">Budgeted?</label>
		<div class="row">
			<label class="columns small-6"><input type="radio" name="budgeted" value="1" {{ questionnaire.budgeted ? 'checked="checked "' : '' }}/>Yes</label>
			<label class="columns small-6"><input type="radio" name="budgeted" value="0" {{ questionnaire.budgeted is sameas(0) ? 'checked="checked "' : '' }}/>No</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="columns large-3"><label for="oem">OEM / ODM</label><input value="{{ questionnaire.oem }}" id="oem" type="text" name="oem" /></div>
</div>
<div class="row">
	<div class="columns large-6"><label for="convince">Still to convince</label><input value="{{ questionnaire.convince }}" id="convince" type="text" name="convince" /></div>
	<div class="columns large-6"><label for="tasks">Tasks</label><textarea id="tasks" name="tasks">{{ questionnaire.tasks }}</textarea></div>
</div>
<div class="row">
	<div class="columns large-6"><label for="generalNotes">General Notes</label><textarea id="generalNotes" name="generalNotes">{{ questionnaire.generalNotes }}</textarea></div>
	<div class="columns large-6"><label for="riskAssessment">Risk Assessment </label><textarea id="riskAssessment" name="riskAssessment">{{ questionnaire.riskAssessment }}</textarea></div>
</div>
<h3>To {{ $adjuster_case->adjuster->name}} </h3>

<p>This case has to assigned to you. Please check in your account<p>
<ul>
	<li>Case Number : {{ $adjuster_case->case->case_number }}</li>
	<li>Insurance   : {{ $adjuster_case->case->insurance->insurance_name }}</li>
	<li>Title 		: {{ $adjuster_case->case->title }}</li>
	<li>Detail 		: {{ url('/') }}</li>
</ul>

<p>Best Regards</p>
<h1>&nbsp;</h1>
<p>Atlas Team</p>
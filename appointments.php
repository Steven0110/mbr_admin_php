<?php
include 'head.php';



?>

<link rel="stylesheet" href="css/jqx.base.css" type="text/css" />
<!--script type="text/javascript" src="../../scripts/jquery-1.11.1.min.js"></script-->
<script type="text/javascript" src="js/scheduler/demos.js"></script>
<script type="text/javascript" src="js/scheduler/jqxcore.js"></script>
<script type="text/javascript" src="js/scheduler/jqxbuttons.js"></script>
<script type="text/javascript" src="js/scheduler/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/scheduler/jqxdata.js"></script>
<script type="text/javascript" src="js/scheduler/jqxdate.js"></script>
<script type="text/javascript" src="js/scheduler/jqxscheduler.js"></script>
<script type="text/javascript" src="js/scheduler/jqxscheduler.api.js"></script>
<script type="text/javascript" src="js/scheduler/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="js/scheduler/jqxmenu.js"></script>
<script type="text/javascript" src="js/scheduler/jqxcalendar.js"></script>
<script type="text/javascript" src="js/scheduler/jqxtooltip.js"></script>
<script type="text/javascript" src="js/scheduler/jqxwindow.js"></script>
<script type="text/javascript" src="js/scheduler/jqxcheckbox.js"></script>
<script type="text/javascript" src="js/scheduler/jqxlistbox.js"></script>
<script type="text/javascript" src="js/scheduler/jqxdropdownlist.js"></script>
<script type="text/javascript" src="js/scheduler/jqxnumberinput.js"></script>
<script type="text/javascript" src="js/scheduler/jqxradiobutton.js"></script>
<script type="text/javascript" src="js/scheduler/jqxinput.js"></script>
<!--script type="text/javascript" src="js/scheduler/globalization/globalize.js"></script-->
<!--script type="text/javascript" src="js/scheduler/globalization/globalize.culture.es-ES.js"></script-->

<div class="sectionTitle">CONTROL DE CITAS</div>

<div id="scheduler"></div>

<script type="text/javascript">
	$(document).ready(function () {
		var appointments = new Array();

		var appointment1 = {
			id: "id1",
			description: "George brings projector for presentations.",
			location: "",
			subject: "Quarterly Project Review Meeting",
			calendar: "Room 1",
			start: new Date(2017, 02, 23, 9, 0, 0),
			end: new Date(2017, 02, 23, 16, 0, 0)
		}

		var appointment2 = {
			id: "id2",
			description: "",
			location: "",
			subject: "IT Group Mtg.",
			calendar: "Room 2",
			start: new Date(2017, 02, 17, 10, 0, 0),
			end: new Date(2017, 02, 17, 15, 0, 0)
		}

		var appointment3 = {
			id: "id3",
			description: "",
			location: "",
			subject: "Course Social Media",
			calendar: "Room 3",
			start: new Date('2017-02-17 16:00:00'),
			end: new Date('2017-02-17 17:00:00')
		}

		var appointment4 = {
			id: "id4",
			description: "",
			location: "",
			subject: "New Projects Planning",
			calendar: "Room 2",
			start: new Date('2017-02-23 16:00:00'),
			end: new Date('2017-02-23 18:00:00')
		}

		var appointment5 = {
			id: "id5",
			description: "",
			location: "",
			subject: "Interview with James",
			calendar: "Room 1",
			start: new Date(2017, 02, 25, 15, 0, 0),
			end: new Date(2017, 02, 25, 17, 0, 0)
		}

		var appointment6 = {
			id: "id6",
			description: "",
			location: "",
			subject: "Interview with Nancy",
			calendar: "Room 4",
			start: new Date(2017, 02, 26, 14, 0, 0),
			end: new Date(2017, 02, 26, 16, 0, 0)
		}
		appointments.push(appointment1);
		appointments.push(appointment2);
		appointments.push(appointment3);
		appointments.push(appointment4);
		appointments.push(appointment5);
		appointments.push(appointment6);


		// prepare the data
		var source =
		{
			dataType: "array",
			dataFields: [
				{ name: 'id', type: 'string' },
				{ name: 'description', type: 'string' },
				{ name: 'location', type: 'string' },
				{ name: 'subject', type: 'string' },
				{ name: 'calendar', type: 'string' },
				{ name: 'start', type: 'date' },
				{ name: 'end', type: 'date' }
			],
			id: 'id',
			localData: appointments
		};
		var adapter = new $.jqx.dataAdapter(source);
		$("#scheduler").jqxScheduler({
			date: new $.jqx.date('todayDate'),
			width: '100%',
			height: 800,
			source: adapter,
			view: 'weekView',
			showLegend: true,
			/**
			 * called when the context menu is created.
			 * @param {Object} menu - jqxMenu's jQuery object.
			 * @param {Object} settings - Object with the menu's initialization settings.
			*/
			contextMenuCreate: function(menu, settings)
			{
				var source = settings.source;
				source.push({ id: "delete", label: "Delete Appointment" });
				source.push({
					id: "status", label: "Set Status", items:
						[
							{ label: "Free", id: "free" },
							{ label: "Out of Office", id: "outOfOffice" },
							{ label: "Tentative", id: "tentative" },
							{ label: "Busy", id: "busy" }
						]
				});
			},
			/**
			 * called when the user clicks an item in the Context Menu. Returning true as a result disables the built-in Click handler.
			 * @param {Object} menu - jqxMenu's jQuery object.
			 * @param {Object} the selected appointment instance or NULL when the menu is opened from cells selection.
			 * @param {jQuery.Event Object} the jqxMenu's itemclick event object.
		   */
			contextMenuItemClick: function (menu, appointment, event)
			{
				var args = event.args;
				switch (args.id) {
					case "delete":
						$("#scheduler").jqxScheduler('deleteAppointment', appointment.id);
						return true;
					case "free":
						$("#scheduler").jqxScheduler('setAppointmentProperty', appointment.id, 'status', 'free');
						return true;
					case "outOfOffice":
						$("#scheduler").jqxScheduler('setAppointmentProperty', appointment.id, 'status', 'outOfOffice');
						return true;
					case "tentative":
						$("#scheduler").jqxScheduler('setAppointmentProperty', appointment.id, 'status', 'tentative');
						return true;
					case "busy":
						$("#scheduler").jqxScheduler('setAppointmentProperty', appointment.id, 'status', 'busy');
						return true;
				}
			},
			/**
			 * called when the menu is opened.
			 * @param {Object} menu - jqxMenu's jQuery object.
			 * @param {Object} the selected appointment instance or NULL when the menu is opened from cells selection.
			 * @param {jQuery.Event Object} the open event.
			*/
			contextMenuOpen: function (menu, appointment, event) {

				if (!appointment) {
					menu.jqxMenu('hideItem', 'delete');
					menu.jqxMenu('hideItem', 'status');
				}
				else {
					menu.jqxMenu('showItem', 'delete');
					menu.jqxMenu('showItem', 'status');
				}
			},
			/**
			 * called when the menu is closed.
			 * @param {Object} menu - jqxMenu's jQuery object.
			 * @param {Object} the selected appointment instance or NULL when the menu is opened from cells selection.
			  * @param {jQuery.Event Object} the close event.
		   */
			contextMenuClose: function (menu, appointment, event) {
			},
			resources:
			{
				colorScheme: "scheme02",
				dataField: "calendar",
				source: new $.jqx.dataAdapter(source)
			},
			appointmentDataFields:
			{
				from: "start",
				to: "end",
				id: "id",
				description: "description",
				location: "place",
				subject: "subject",
				resourceId: "calendar"
			},
			views:
			[
				'dayView',
				'weekView',
				'monthView'
			]
		});
		
		$("#scheduler").on('appointmentAdd', function (event) {
                var args = event.args;
                var appointment = args.appointment;
				alert(JSON.stringify(appointment));
            });
	});
</script>
    
<?php include 'footer.php'; ?>
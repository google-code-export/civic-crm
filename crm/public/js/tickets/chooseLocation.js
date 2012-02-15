"use strict";
/**
 * Opens a new window for the user to lookup a location
 *
 * When the user finally selects a location, the HTML is supposed
 * to call the callback function, LOCATION_CHOOSER.setLocation().
 *
 * Every HTML block involved needs to pass along the callback parameter.
 * Any link or action that can be considered selecting a person should
 * use the callback function, instead of it's normal href.
 *
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
var LOCATION_CHOOSER = {
	popup: {},
	setLocation: function (location) {
		YUI().use('node', 'io', function (Y) {
			var locationPanel = Y.one('#location-panel');
			locationPanel.setContent('<img src="' + BASE_URL + '/skins/local/images/busy.gif" />');

			Y.io(BASE_URL + '/locations/view?partial=location-panel;disableLinks=1;location=' + location, {
				on: {
					complete: function (id, o, args) {
						locationPanel.setContent(o.responseText);
						var location = locationPanel.one('.locationInfo h1 a');
						document.getElementById('ticket-location').value = location.getContent();

						LOCATION_CHOOSER.popup.close();
					}
				}
			});
		});
	}
};
YUI().use('node', function (Y) {
	Y.on('click', function (e) {
		LOCATION_CHOOSER.popup = window.open(
			BASE_URL + '/locations?popup=1;callback=LOCATION_CHOOSER.setLocation',
			'popup',
			'menubar=no,location=no,status=no,width=800,height=600'
		);
		e.preventDefault();
	}, '#findAddressButton');
});

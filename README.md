# jira-ticket-dashboard
Ticket dashboard Add On for Jira service desk.
Due to limited time, I will only explain the flow. For the details, you can always read the code.
If you need to store other data, you can always change the code.
If you have any question, feel free to leave me a message.

Configure web hook at project automation or jira event.
Whenever event trigger the web hook action, event data will be store into the SQL lite file.

![alt text](https://github.com/foongws/jira-ticket-dashboard/blob/master/documentation/Data-Incoming.png)

When user visit ticketingdashboard.php with parameters, the corresponding ticket queue will be show in the dashboard and refresh every 10 secs. At the same time server sent event will be subscribe. The server sent event will be base on the session id to send event.

![alt text](https://github.com/foongws/jira-ticket-dashboard/blob/master/documentation/Ticket%20Dashboard%20and%20alert.png)

4 files is all you need. comment.db, ticketingdashboard.php, tickets.php, ticketstream.php put in the same folder, should be start working.

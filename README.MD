# Appointment Scheduler External Module
 

### What does it do?
This custom module will allow Admins to create/import office hours in REDCap project and present 
these hours to the public. Public users can reserve these hours for training or consulting.

User can view/manage her/his reservations. 

Admins can view/manage/reschedule her/his or other calendars. 


### REDCap Project

#### Data Collection Instruments
You need to create a REDCap project contains following fields:

1. Start Time (start)
2. End Time (end)
3. Instructor (instructor)
4. Location (location)
5. Notes (notes)
6. Log (log)
7. Number of Participants (number_of_participants)
8. Slot status (slot_status)



#### Events
You need to create type of office hours you need as events. **Make sure to designate the project instrument to the created events**


### Database changes 
Few table got create. Please make sure to run the SQL statements in **/database/Appointment_Scheduler.sql**. 




# Project Manager

This personal side quest came out of the desire to see a project management web app that behaved the way I would like it to. With some feedback from my awesome team and their ideal project management app experience.
The other inspiration came from listening to CGP Grey and Myke Hurley on the Cortex podcast talk about tasks being independent, composable pieces of state that are not tied to a specific project. I'm paraphrasing here, but that really stuck with me as a useful property of a task manager. Break the task up into as many pieces as you need and assign just those pieces to whoever. And they don't need to see or care about the surrounding context of tasks.

## Features It Has

The data model is based off of an organization that all users are associated to. With one Super Admin. Users may then be admins of projects or associated with projects. And of course assigned tasks. Because duh.

Currently working features:
 - User Registration
  1. Organization creation flow
   - Create Organization with User as Super Admin
   - Create Private Tasks Project for User 
  2. Organization invite flow
   - Allow Super Admin to put an email in which sends a link to the email to register
   - When User is created, they get added to the Organization
   - Create Private Tasks Project for User
 - Create new Projects
 - Two main pages:
  1. "Your Tasks" - shows just tasks you are assigned to across all projects/buckets/etc
  2. "Project" - shows all tasks that happen to be associated with a project and/or bucket in a project
 - Two different styles of displaying tasks within both of those kinds of pages:
  1. Board view: think kanban/Trello/Planner, organized by bucket in a project
  2. Grid view: table of tasks
 - Task Composabililty
  - Tasks can have an arbitrary number/level of subtasks that have their own assignments/due dates/priority or even bucket/project assignment
  - Tasks/Subtasks can be assigned to any one in your organization and it will show either in their "Your Tasks" page or if you attach it to a project and/or bucket they have access to, it will show there

## Features I Need

I have some essential features I need to implement before it's usable (without stubbing data):
 - Organization Resource
  1. Allow adding/removing more Users as Super Admins (provided there's always one)
 - Project Resource
  1. Rename/Delete existing Projects
  2. Add/Remove Users from Projects
  3. Transfer Project administration
 - Bucket Resource
  1. Rename/Delete Buckets from Projects
  2. Order Buckets
 - Task Resource
  1. Order Tasks
  2. Put completed Tasks in a hidden section

## Features I Want

Still have features I want to get to (not required for minimum functionality):
 - Attachments - Tasks/Subtasks should have attachments
 - Comments - Tasks/Subtasks should have comments.
 - Notifications - When a task is assigned to you, marked completed, or is commented on, you should get an email

## WIP

Still very much a work in progress, but I'm really happy with how far it's come. Deployed on Laravel Cloud giving a link to friends/co-workers to try out.

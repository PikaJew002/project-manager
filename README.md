# Project Manager

This personal side quest came out of the desire to see a project management web app that behaved the way I would like it to. With some feedback from my awesome team and their ideal project management app experience.
The other inspiration came from listening to CGP Grey and Myke Hurley on the Cortex podcast talk about tasks being independent, composable pieces of state that are not tied to a specific project. I'm paraphrasing here, but that really stuck with me as a useful property of a task manager. Break the task up into as many pieces as you need and assign just those pieces to whoever. And they don't need to see or care about the surrounding context of tasks.

## Features

The data model is based off of an organization that all users are associated to. With one Super Admin. Users may then be admins of projects or associated with projects. And of course assigned tasks. Because duh.

Currently working features:
 - Two main pages:
  1. "Your Tasks" - shows just tasks you are assigned to across all projects/buckets/etc
  2. "Project" - shows all tasks that happen to be associated with a project and/or bucket in a project
 - Two different styles of displaying tasks within both of those kinds of pages:
  1. Board view: think kanban/Trello/Planner, organized by bucket in a project
  2. Grid view: table of tasks
 - Task Composabililty
  - Tasks can have an arbitrary number/level of subtasks that have their own assignments/due dates/priority or even bucket/project assignment
  - Tasks/Subtasks can be assigned to any one in your organization and it will show either in their "Your Tasks" page or if you attach it to a project and/or bucket they have access to, it will show there

## Features I Want

Still have features I want to get to:
 - Attachments - Tasks/Subtasks should have attachments
 - Comments - Tasks/Subtasks should have comments.
 - Notifications - When a task is assigned to you, marked completed, or is commented on, you should get an email

## WIP

Still very much a work in progress, but I'm really happy with how far it's come. Close to deploying it on a Droplet (or maybe Laravel Cloud?) and giving a link to friends/co-workers to try out.

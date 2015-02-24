# Testing React components in Airlines

We need to be able to :

- mock a component's methods, to provide dummy data instead of calling the server (unit testing)
- test the effect of DOM events targeting one component on another one (integration testing)

Some tests may be doable now - with [jasmine-react](https://github.com/tommyh/jasmine-react) - as they require no mocking, or mocking children components only. Here is an [useful reference](http://developer.telerik.com/featured/journey-client-side-testing-javascript/).

Written tests will be marked with ✓.

## Test cases

### Board

- Mock `loadDates` and check the Board's heading rendering
- Mock `loadMembers` and check the Members rendering
- Change the title and check `updateName` is called
- Inject some Members, Days and Tasks and check the Members' Numbers' values
- Drag-drop a Task from one Member to another and check again

### Member

- Check the Days rendering
- Inject some Days and Tasks and check the Numbers' values
- Edit a Task's Numbers and check again
- Drag-drop a Task from one Day to another and check again, also check `updateNumbers` is called, and `updateNumbers` and `updateTasks` are called for both days

### Day

- Start dropping a Task and check CSS classes
- Drop a Task and check `move` is called for it

### Task

- Start dragging and check CSS classes
- Start dropping one Task on another and check CSS classes
- Finish dropping and check `merge` is called
- Change the title and check `update` is called, do the same for the Numbers
- Click the corresponding link and check `split` is called
- Click the corresponding link and check `remove` is called

### Numbers

- Check the right CSS classes are applied depending on the props' values

### Editable

- Change the value and check `handleInput is called`

TODO

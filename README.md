# Brain Rush

## Structure

### Users

#### Data structure

- `id` - integer
- `username` - string
- `email` - string
- `password` - string

#### Relations

- `belongsToMany` - `groups`

#### Functionality

- User can register
- User can login
- User can logout
- User can change password
- User can delete account and all related data

### Groups

#### Data structure

- `id` - integer
- `name` - string
- `description` - string

#### Relations

- `belongsToMany` - `users`

#### Functionality

- User can create group
- User can delete group
- User can update group
- User can leave group
- User can accept invite to group
- User can decline invite to group
- User can invite user to group
- User can remove user from group
- New owner needs to be chosen if current owner leaves group
- User can assign a new owner to group

### GroupUser

#### Data structure

- `id` - integer
- `group_id` - integer
- `user_id` - integer
- `type` - enum (owner, member, invited)

### Quizzes

#### Data structure

- `id` - integer
- `name` - string
- `description` - string
- `group_id` - integer
- `user_id` - integer
- `is_public` - boolean
- `is_online`: boolean

#### Relations

- `belongsTo` - `groups`
- `belongsTo` - `users`
- `hasMany` - `questions`
- `belongsToMany` - `users`

#### Functionality

- User can create quiz
- User can delete quiz
- User can update quiz
- User can start quiz
- User can stop quiz
- User can invite user to quiz
- User can invite group to quiz (invites all users in group)
- User can remove user from quiz
- User can accept invite to quiz
- User can decline invite to quiz
- User can leave quiz
- User can view statistics of quiz

### Questions

#### Data structure

- `id` - integer
- `quiz_id` - integer
- `question` - string
- `type` - enum (multiple_choice, single_choice, open, true_or_false)
- `time_limit` - integer

#### Relations

- `belongsTo` - `quizzes`
- `hasMany` - `choices`
- `hasMany` - `answers`

### Functionality

- User can create question
- User can delete question
- User can update question

### Choices

#### Data structure

- `id` - integer
- `question_id` - integer
- `choice` - string
- `is_correct` - boolean

#### Relations

- `belongsTo` - `questions`

#### Functionality

- User can create choice
- User can delete choice
- User can update choice
- User can mark choice as correct/incorrect

### Answers

#### Data structure

- `id` - integer
- `session_id` - integer
- `question_id` - integer
- `session_question_id` - integer
- `user_id` - integer
- `choice_id` - string
- `answer` - string
- `is_correct` - boolean

#### Relations

- `belongsTo` - `questions`
- `belongsTo` - `users`
- `belongsTo` - `choices`

#### Functionality

- User can create answer

### Quiz sessions

#### Data structure

- `id` - integer`
- `quiz_id` - integer
- `user_id` - integer
- `started_at` - datetime
- `ended_at` - datetime
- `is_active` - boolean

#### Relations

- `belongsTo` - `quizzes`
- `hasMany` - `quiz_session_users`
- `hasMany` - `answers`

#### Functionality

- User can start quiz session
- User can stop quiz session
- User can view statistics of quiz session

### Quiz session users

#### Data structure

- `id` - integer
- `quiz_session_id` - integer
- `user_id` - integer
- `is_active` - boolean

#### Relations

- `belongsTo` - `quiz_sessions`
- `belongsTo` - `users`
- `hasMany` - `answers`

### Quiz session questions

#### Data structure

- `id` - integer
- `quiz_session_id` - integer
- `question_id` - integer
- `is_active` - boolean
- `started_at` - datetime
- `ended_at` - datetime

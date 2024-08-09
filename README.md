
## Standard Optics Retail System


A personal project dedicated for Standard Optical Centre. The system is mainly a e-KYC system, but also can record sales data.

Here's a list of the most commonly used Git commands:

### 1. **Basic Git Commands**
- `git init`: Initializes a new Git repository.
- `git clone <repository-url>`: Clones a repository from a remote URL.
- `git status`: Shows the status of changes as untracked, modified, or staged.
- `git add <file>`: Stages a file for the next commit.
- `git commit -m "message"`: Commits the staged changes with a descriptive message.
- `git push origin <branch>`: Pushes the local branch to the remote repository.
- `git pull origin <branch>`: Fetches and merges changes from the remote branch to the local branch.
- `git branch`: Lists all local branches.
- `git checkout <branch>`: Switches to the specified branch and updates the working directory.
- `git merge <branch>`: Merges the specified branch’s history into the current branch.
- `git log`: Shows the commit history for the repository.

### 2. **Branch Management**
- `git branch <branch-name>`: Creates a new branch.
- `git branch -d <branch-name>`: Deletes a branch locally.
- `git push origin --delete <branch-name>`: Deletes a branch remotely.
- `git checkout -b <branch-name>`: Creates and switches to a new branch.

### 3. **Stashing & Cleaning**
- `git stash`: Temporarily saves changes that are not yet ready to commit.
- `git stash apply`: Applies the stashed changes to the current working directory.
- `git stash drop`: Deletes the stashed changes.
- `git clean -f`: Removes untracked files from the working directory.

### 4. **Remote Repositories**
- `git remote add origin <url>`: Adds a remote repository.
- `git remote -v`: Shows all remote repositories associated with the local repository.
- `git fetch`: Fetches changes from the remote repository without merging them.
- `git pull --rebase`: Fetches changes from the remote repository and rebases your local commits on top.

### 5. **Resetting & Reverting**
- `git reset --hard <commit>`: Resets the index and working directory to the specified commit.
- `git reset --soft <commit>`: Resets the index to the specified commit but leaves the working directory as is.
- `git revert <commit>`: Reverts the specified commit and generates a new commit with the reverted changes.

### 6. **Tagging**
- `git tag <tag-name>`: Creates a new tag at the current commit.
- `git tag -d <tag-name>`: Deletes a tag locally.
- `git push origin <tag-name>`: Pushes a tag to the remote repository.
- `git push origin --tags`: Pushes all tags to the remote repository.

These commands cover most of the basic and intermediate operations in Git.
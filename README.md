Auto login bundle packed in a symfony standard framework
======================

Usage:

- Browse to `/app_dev.php`
- When you see the user/password box, you can use `admin:kitten` or `ryan:ryanpass` credentials to login.
- Clear browser (like session cookies)
- Browse to `/app_dev.php?auto_login=cnlhbjpyeWFucGFzcw==`
- This should automatically log you in as user `ryan` without dialog box

Note:
All the fun stuff is located in the `src/Rainbow/SecurityBundle/Firewall` directory
 

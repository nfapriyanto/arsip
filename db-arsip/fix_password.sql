-- Fix password untuk user admin dan user
-- Password default: admin = 'admin', user = 'user'

-- Update password admin (MD5 dari 'admin')
UPDATE `tb_user` SET `password` = MD5('admin') WHERE `username` = 'admin';

-- Update password user (MD5 dari 'user')  
UPDATE `tb_user` SET `password` = MD5('user') WHERE `username` = 'user';

-- Atau jika ingin menggunakan password yang lebih aman, uncomment baris berikut:
-- UPDATE `tb_user` SET `password` = MD5('admin123') WHERE `username` = 'admin';
-- UPDATE `tb_user` SET `password` = MD5('user123') WHERE `username` = 'user';







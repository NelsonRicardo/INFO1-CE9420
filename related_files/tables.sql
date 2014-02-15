drop table if exists nr_fees;
drop table if exists nr_expenses;
drop table if exists nr_invoices;
drop table if exists nr_task_codes;
drop table if exists nr_phase_codes;
drop table if exists nr_activity_codes;
drop table if exists nr_expense_codes;
drop table if exists nr_users;

create table nr_invoices
(
inv_id int not null auto_increment primary key,
lf_id char(20) not null,
inv_num char(20) not null,
inv_date date not null,
client_id char(20) not null,
lf_matt_id char(20) not null,
clnt_matt_id char(20),
inv_tot decimal(16,4) not null,
from_date date not null,
thru_date date not null,
inv_desc varchar(15000),
unique key (lf_id, inv_num, client_id)
) engine=InnoDB;

create table nr_phase_codes
(
phase_code char(20) not null primary key,
phase_desc varchar(255) not null
) engine = InnoDB;

create table nr_task_codes
(
task_code char(20) not null primary key,
task_desc varchar(255) not null,
phase_code char(20) not null,
foreign key (phase_code) references nr_phase_codes(phase_code)
) engine=InnoDB;

create table nr_activity_codes
(
activity_code char(20) not null primary key,
activity_desc varchar(255)
) engine=InnoDB;

create table nr_fees
(
fee_id int not null auto_increment primary key,
inv_id int not null,
item_num int not null,
item_type char(2) not null,
item_units decimal(14, 4) not null,
item_rate decimal(14, 4) not null,
item_adj decimal(14, 4) not null,
item_tot decimal(14, 4) not null,
item_date date not null,
task_code char(20),
act_code char(20),
tkpr_id char(20),
tkpr_name varchar(30),
tkpr_class varchar(10),
item_desc varchar(15000),
foreign key (inv_id) references nr_invoices(inv_id),
unique key (inv_id, item_num)
) engine=InnoDB;

create table nr_expense_codes
(
exp_code char(20) not null primary key,
exp_desc varchar(255) not null
) engine=InnoDB;

create table nr_expenses
(
exp_id int not null auto_increment primary key,
inv_id int not null,
item_num int not null,
item_type char(2) not null,
item_units decimal(14, 4) not null,
item_rate decimal(14, 4) not null,
item_adj decimal(14, 4) not null,
item_tot decimal(14, 4) not null,
item_date date not null,
exp_code char(20),
item_desc varchar(15000),
foreign key (inv_id) references nr_invoices(inv_id),
unique key (inv_id, item_num)
) engine=InnoDB;

create table nr_users
(
user_name varchar(40) not null primary key,
first_name varchar(40) not null,
last_name varchar(40) not null,
password varchar(40) not null
) engine=InnoDB;

insert into nr_phase_codes (phase_code, phase_desc) values ('L100', 'Case Assessment, Development and Administration');
insert into nr_phase_codes (phase_code, phase_desc) values ('L200', 'Pre-Trial Pleadings and Motions');
insert into nr_phase_codes (phase_code, phase_desc) values ('L300', 'Discovery');
insert into nr_phase_codes (phase_code, phase_desc) values ('L400', 'Trial Preparation and Trial');
insert into nr_phase_codes (phase_code, phase_desc) values ('L500', 'Appeal');
insert into nr_phase_codes (phase_code, phase_desc) values ('C100', 'Fact Gathering');
insert into nr_phase_codes (phase_code, phase_desc) values ('C200', 'Researching Law');
insert into nr_phase_codes (phase_code, phase_desc) values ('C300', 'Analysis and Advice');
insert into nr_phase_codes (phase_code, phase_desc) values ('C400', 'Third Party Communication');
insert into nr_phase_codes (phase_code, phase_desc) values ('P100', 'Project Administration');
insert into nr_phase_codes (phase_code, phase_desc) values ('P200', 'Fact Gathering/Due Diligence');
insert into nr_phase_codes (phase_code, phase_desc) values ('P300', 'Structure/Strategy/Analysis');
insert into nr_phase_codes (phase_code, phase_desc) values ('P400', 'Initial Document Preparation/Filing');
insert into nr_phase_codes (phase_code, phase_desc) values ('P500', 'Negotiation/Revision/Responses');
insert into nr_phase_codes (phase_code, phase_desc) values ('P600', 'Completion/Closing');
insert into nr_phase_codes (phase_code, phase_desc) values ('P700', 'Post-Completion/Post-Closing');
insert into nr_phase_codes (phase_code, phase_desc) values ('P800', 'Maintenance and Renewal');
insert into nr_phase_codes (phase_code, phase_desc) values ('B100', 'Administration');
insert into nr_phase_codes (phase_code, phase_desc) values ('B200', 'Operations');
insert into nr_phase_codes (phase_code, phase_desc) values ('B300', 'Claims and Plan');
insert into nr_phase_codes (phase_code, phase_desc) values ('B400', 'Bankruptcy-Related Advice');

insert into nr_task_codes (task_code, task_desc, phase_code) values ('L110', 'Fact Investigation/Development', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L120', 'Analysis/Strategy', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L130', 'Experts/Consultants', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L140', 'Document/File Management', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L150', 'Budgeting', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L160', 'Settlement/Non-Binding ADR', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L190', 'Other Case Assessment, Development and Administration', 'L100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L210', 'Pleadings', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L220', 'Preliminary Injunctions/Provisional Remedies', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L230', 'Court Mandated Conferences', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L240', 'Dispositive Motions', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L250', 'Other Written Motions and Submissions', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L260', 'Class Action Certification and Notice', 'L200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L310', 'Written Discovery', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L320', 'Document Production', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L330', 'Depositions', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L340', 'Expert Discovery', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L350', 'Discovery Motions', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L390', 'Other Discovery', 'L300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L410', 'Fact Witnesses', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L420', 'Expert Witnesses', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L430', 'Written Motions and Submissions', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L440', 'Other Trial Preparation and Support', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L450', 'Trial and Hearing Attendance', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L460', 'Post-Trial Motions and Submissions', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L470', 'Enforcement', 'L400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L510', 'Appellate Motions and Submissions', 'L500');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L520', 'Appellate Briefs', 'L500');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('L530', 'Oral Argument', 'L500');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('C100', 'Fact Gathering', 'C100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('C200', 'Researching Law', 'C200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('C300', 'Analysis and Advice', 'C300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('C400', 'Third Party Communication', 'C400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P100', 'Project Administration', 'P100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P210', 'Corporate Review', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P220', 'Tax', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P230', 'Environmental', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P240', 'Real and Personal Property', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P250', 'Employee/Labor', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P260', 'Intellectual Property', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P270', 'Regulatory Reviews', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P280', 'Other', 'P200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P300', 'Structure/Strategy/Analysis', 'P300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P400', 'Initial Document Preparation/Filing', 'P400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P500', 'Negotiation/Revision/Responses', 'P500');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P600', 'Completion/Closing', 'P600');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P700', 'Post-Completion/Post-Closing', 'P700');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('P800', 'Maintenance and Renewal', 'P800');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B110', 'Case Administration', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B120', 'Asset Analysis and Recovery', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B130', 'Asset Disposition', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B140', 'Relief from Stay/Adequate Protection Proceedings', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B150', 'Meetings of and Communications with Creditors', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B160', 'Fee/Employment Applications', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B170', 'Fee/Employment Objections', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B180', 'Avoidance Action Analysis', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B185', 'Assumption/Rejection of Leases and Contracts', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B190', 'Other Contested Matters (excluding assumption/rejection motions)', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B195', 'Non-Working Travel', 'B100');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B210', 'Business Operations', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B220', 'Employee Benefits/Pensions', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B230', 'Financing/Cash Collections', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B240', 'Tax Issues', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B250', 'Real Estate', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B260', 'Board of Directors Matters', 'B200');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B310', 'Claims Administration and Objections', 'B300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B320', 'Plan and Disclosure Statement (including Business Plan)', 'B300');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B410', 'General Bankruptcy Advice/Opinions', 'B400');
insert into nr_task_codes (task_code, task_desc, phase_code) values ('B420', 'Restructurings', 'B400');

insert into nr_activity_codes (activity_code, activity_desc) values ('A101', 'Plan and prepare for');
insert into nr_activity_codes (activity_code, activity_desc) values ('A102', 'Research');
insert into nr_activity_codes (activity_code, activity_desc) values ('A103', 'Draft/revise');
insert into nr_activity_codes (activity_code, activity_desc) values ('A104', 'Review/analyze');
insert into nr_activity_codes (activity_code, activity_desc) values ('A105', 'Communicate (in firm)');
insert into nr_activity_codes (activity_code, activity_desc) values ('A106', 'Communicate (with client)');
insert into nr_activity_codes (activity_code, activity_desc) values ('A107', 'Communicate (other outside counsel)');
insert into nr_activity_codes (activity_code, activity_desc) values ('A108', 'Communicate (other external)');
insert into nr_activity_codes (activity_code, activity_desc) values ('A109', 'Appear for/attend');
insert into nr_activity_codes (activity_code, activity_desc) values ('A110', 'Manage data/files');
insert into nr_activity_codes (activity_code, activity_desc) values ('A111', 'Other');

insert into nr_expense_codes (exp_code, exp_desc) values ('E101', 'Copying');
insert into nr_expense_codes (exp_code, exp_desc) values ('E102', 'Outside printing');
insert into nr_expense_codes (exp_code, exp_desc) values ('E103', 'Word processing');
insert into nr_expense_codes (exp_code, exp_desc) values ('E104', 'Facsimile');
insert into nr_expense_codes (exp_code, exp_desc) values ('E105', 'Telephone');
insert into nr_expense_codes (exp_code, exp_desc) values ('E106', 'Online research');
insert into nr_expense_codes (exp_code, exp_desc) values ('E107', 'Delivery services/messengers');
insert into nr_expense_codes (exp_code, exp_desc) values ('E108', 'Postage');
insert into nr_expense_codes (exp_code, exp_desc) values ('E109', 'Local travel');
insert into nr_expense_codes (exp_code, exp_desc) values ('E110', 'Out-of-town travel');
insert into nr_expense_codes (exp_code, exp_desc) values ('E111', 'Meals');
insert into nr_expense_codes (exp_code, exp_desc) values ('E112', 'Court fees');
insert into nr_expense_codes (exp_code, exp_desc) values ('E113', 'Subpoena fees');
insert into nr_expense_codes (exp_code, exp_desc) values ('E114', 'Witness fees');
insert into nr_expense_codes (exp_code, exp_desc) values ('E115', 'Deposition transcripts');
insert into nr_expense_codes (exp_code, exp_desc) values ('E116', 'Trial transcripts');
insert into nr_expense_codes (exp_code, exp_desc) values ('E117', 'Trial exhibits');
insert into nr_expense_codes (exp_code, exp_desc) values ('E118', 'Litigation support vendors');
insert into nr_expense_codes (exp_code, exp_desc) values ('E119', 'Experts');
insert into nr_expense_codes (exp_code, exp_desc) values ('E120', 'Private investigators');
insert into nr_expense_codes (exp_code, exp_desc) values ('E121', 'Arbitrators/mediators');
insert into nr_expense_codes (exp_code, exp_desc) values ('E122', 'Local counsel');
insert into nr_expense_codes (exp_code, exp_desc) values ('E123', 'Other professionals');
insert into nr_expense_codes (exp_code, exp_desc) values ('E124', 'Other');

insert into nr_users (user_name, first_name, last_name, password) values ('nelson', 'Nelson', 'Ricardo', sha1('nelson'));
insert into nr_users (user_name, first_name, last_name, password) values ('sam', 'Sam', 'Sultan', sha1('sam'));
insert into nr_users (user_name, first_name, last_name, password) values ('trixie', 'Patricia', 'Brown', sha1('trixie'));
insert into nr_users (user_name, first_name, last_name, password) values ('nancy', 'Nancy', 'Bretsch', sha1('nancy'));

select * from nr_invoices;
select * from nr_fees;
select * from nr_expenses;
select * from nr_phase_codes;
select * from nr_task_codes;
select * from nr_activity_codes;
select * from nr_expense_codes;
select * from nr_users;
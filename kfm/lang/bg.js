/*
 * See ../license.txt for licensing
 *
 * For further information visit:
 * 	http://kfm.verens.com/
 *
 * File Name: bg.js
 * 	Bulgarian language file.
 *
 * File Authors:
 * 	tondy@tondy.com
 */

kfm.lang=
{
Dir:
	"ltr", // language direction
ErrorPrefix:
	"грешка: ",
// what you see on the main page
Directories:
	"Папки",
CurrentWorkingDir:
	"Текуща папка: \"%1\"",
Logs:
	"Логове",
FileUpload:
	"Качване на файл",
DirEmpty:
	"Няма файлове в \"%1\"",

// right click menu item directory
// directory
CreateSubDir:
	"Създаване на папка",
DeleteDir:
	"Изтриване",
RenameDir:
	"Преименуване",

// file
DeleteFile:
	"Изтрий",
RenameFile:
	"Преименувай",
RotateClockwise:
	"Завърти надясно",
RotateAntiClockwise:
	"Завърти наляво",
ResizeImage:
	"Промени големината на картинката",
ChangeCaption:
	"Промени Заглавието",

// create a file
WhatFilenameToCreateAs:
	"Какъв файл ще създадете?",
AskIfOverwrite:
	"Файлът \"%1\" вече съществува. Да презапиша ли?",
NoForwardslash:
	"\nНе можете да използвате '/' в името на файла",

// messages management
CreateDirMessage:
	"Създаване на папка в \"%1\":",
DelDirMessage:
	"Сигурни ли сте, че искате да изтриете папката \"%1\"?",
DelFileMessage:
	"Сигурни ли сте, че искате да изтриете файла \"%1\"?",
DelMultipleFilesMessage:
	"Сигурни ли сте, че искате да изтриете тези файлове?\n\n'",
DownloadFileFromMessage:
	"Свали файла от адрес?",
FileSavedAsMessage:
	"Записвате файла като ...",

// resize file
CurrentSize:
	"Текущ размер: \"%1\" x \"%2\"\n",
NewWidth:
	"Нова Широчина?",
NewWidthConfirmTxt:
	"Нова Широчина: \"%1\"\n",
NewHeight:
	"Нова Височина?",
NewHeightConfirmTxt:
	"Нова Височина: \"%1\"\nПравилно ли е?",

// log messages
RenamedFile:
	"Преименуван файла \"%1\" на \"%2\".",
DirRefreshed:
	"Актуализиране на папките.",
FilesRefreshed:
	"Актуализиране на файловете.",
NotMoreThanOneFile:
	"грешка: Не можете да изберете повече от един файл",
UnknownPanelState:
	"грешка: Неясно състояние на панела.",
// MissingDirWrapper:
// 	"error: missing directory wrapper: \"kfm_directories%1\".",
SetStylesError:
	"грешка: Не можете да промените \"%1\" на \"%2\.",
NoPanel:
	"грешка: Панела \"%1\" не съществува.",
FileSelected:
	"Избрани файлове: \"%1\"",
Log_ChangeCaption:
	"Промяна името от \"%1\" на \"%2\"",
UrlNotValidLog:
	"грешка: URL трябва да започва с \"http:\"",
MovingFilesTo:
	"Преместване на файловете [\"%1\"] в \"%2\"",

// error messages
DirectoryNameExists:
	"Папка с това име вече съществува.",
FileNameNotAllowd:
	"грешка: Забранен тип на файл.",
CouldNotWriteFile:
	"грешка: Не е записан \"%1\".",
CouldNotRemoveDir:
	"Не мога да изтрия папката.\nУверете се, че е празна",
UrlNotValid:
	"грешка: URL трябва да започва с \"http:\"",
CouldNotDownloadFile:
	"грешка: Не мога да сваля файла \"%1\".",
FileTooLargeForThumb:
	"грешка: Картинката \"%1\" е много голяма за да направя миниатюра. Моля, сменете я с по-малка.",
CouldntReadDir:
	"грешка: не виждам папката",
CannotRenameFile:
	"грешка: не мога да променя \"%1\" на \"%2\"",
FilenameAlreadyExists:
	"грешка: вече същестува файл с това име",

// new in 0.5
EditTextFile:
	"Редактирай текстов файл",
CloseWithoutSavingQuestion:
	"Сигурни ли сте, че искате да затворите без да запишете?",
CloseWithoutSaving:
	"Затвори без записване",
SaveThenClose:
	"Запиши и след това Затвори",
SaveThenCloseQuestion:
	"Сигурни ли сте, че искате да запазите промените?",

// new in 0.6
LockPanels:
	"Заключи панелите",
UnlockPanels:
	"Отключи панелите",
CreateEmptyFile:
	"Създай празен файл",
DownloadFileFromUrl:
	"Свали файла от URL",
DirectoryProperties:
	"Свойства на папката",
SelectAll:
	"Избери всичко",
SelectNone:
	"Не избирай",
InvertSelection:
	"Обърни избраното",
LoadingKFM:
	"Зареждане на KFM",
Name:
	"име",
FileDetails:
	"Информация за файла",
Search:
	"Търсене",
IllegalDirectoryName:
	"Това \"%1\" не е коректно име на папка",
RecursiveDeleteWarning:
	"Папката \"%1\" не е празна\n Сигурни ли сте, че искате да я изтриете заедно със съдържанието и?\n*ПРЕДУПРЕЖДЕНИЕ*\n Тази операция е НЕОТМЕНИМА",
RmdirFailed:
	"Неуспешно изтриване на папка \"%1\"",
DirNotInDb:
	"Папката не е в базата данни",
ShowPanel:
	"Покажи панел \"%1\"",
ChangeCaption:
	"Промени заглавието",
NewDirectory:
	"Нова папка",
Upload:
	"Качване",
NewCaptionIsThisCorrect:
	"Ново Заглавие:\n%1\n\nПравилно ли е?",
Close:
	"Затваряне",
Loading:
	"Зареждане",
AreYouSureYouWantToCloseKFM:
	"Сигурни ли сте, че искате да затворите KFM прозореца?",
PleaseSelectFileBeforeRename:
	"Моля, изберете файл преди да опитвате да променяте име.",
RenameOnlyOneFile:
	"В даден момент, можете да преименувате само един файл",
RenameFileToWhat:
	"Преименувай файла \"%1\" на ...",
NoRestrictions:
	"Без рестрикции",
Filename:
	"Име на файл",
Maximise:
	"Максимизиране",
Minimise:
	"Минимизиране",
AllowedFileExtensions:
	"Разрешени типове на файл",
Filesize:
	"Размер на файла",
MoveDown:
	"Премести надолу",
Mimetype:
	"Тип на медия",
MoveUp:
	"Премести нагоре",
Restore:
	"Възстанови",
Caption:
	"Заглавие",
CopyFromURL:
	"Копирай от URL",
ExtractZippedFile:
	"Разархивирай",

// new in 0.8
ViewImage:
	"Покажи картинката",
ReturnThumbnailToOpener:
	"Подай миниатюра",
AddTagsToFiles:
	"Добави таг на файла",
RemoveTagsFromFiles:
	"Изтрий  таг на файла",
HowWouldYouLikeToRenameTheseFiles:
	"Как искате да преименувате тези файлове?\n\nПример: \"images-***.jpg\" ще преименува файловете на \"images-001.jpg\", \"images-002.jpg\", ...",
YouMustPlaceTheWildcard:
	"Вие трябва да поставите някъде в шаблона на файловите имена заместващия символ *",
YouNeedMoreThan:
	"Трябвят повече от %1 * символа за да се създадат %2 файлови имена",
NoFilesSelected:
	"Няма избрани файлове",
Tags:
	"Тагове",
IfYouUseMultipleWildcards:
	"Ако използвате множество заместващи символи в шаблона на файловите имена, те трябва да бъдат групирани заедно!",
NewCaption:
	"Ново Заглавие",
WhatMaximumSize:
	"Колко да бъде максималния размер?",
CommaSeparated:
	"разделени със запетая",
WhatIsTheNewTag:
	"Какъв да бъде новият таг? За множество тагове, разделете със запетаи.",
WhichTagsDoYouWantToRemove:
	"Кой таг искате да изтриете? Ако са няколко, разделете със запетаи."

,
// New in 0.9
AllFiles: "all files",
AndNMore: "...and %1 more...",
Browse: "Browse...",
ExtractAfterUpload: "extract after upload",
NotAnImageOrImageDimensionsNotReported: "error: not an image, or image dimensions not reported",
PermissionDeniedCannotDeleteFile: "permission denied: cannot delete file",
RenameTheDirectoryToWhat: "Rename the directory '%1' to what?",
RenamedDirectoryAs: "Renamed '%1' as '%2'",
TheFilenameShouldEndWithN: "The filename should end with %1",
WhatFilenameDoYouWantToUse: "What filename do you want to use?"

,
// New in 1.0
ZipUpFiles: "zip up files",
Cancel: "cancel"
	, // new in 1.2
	Icons                   : "icons", // used to select mode of file view
	ListView                : "list-view", // used to select mode of file view
	SendToCms               : "send to CMS", // close KFM and return the selected files to the CMS
	CannotMoveDirectory     : "permission denied: cannot move directory",
	LastModified            : "last modified", // part of File Details
	ImageDimensions         : "image dimensions", // part of File Details
	CouldNotMoveFiles       : "error: could not move file[s]",
	CopyFiles               : "copy files", // when dragging files to a directory, two choices appear - "copy files" and "move files"
	MoveFiles               : "move files",
	AboutKfm                : "about KFM",
	Errors                  : "Errors",
	Ok                      : "OK" // as in "OK / Cancel"
}

/*
 * See ../license.txt for licensing
 *
 * File Name: ru.js
 * 	Russian language file.
 *
 * File Authors:
 * 	
 */

kfm.lang=
{
Dir:
	"ltr", // language direction
ErrorPrefix:
	"ошибка: ",
// what you see on the main page
Directories:
	"Каталоги",
CurrentWorkingDir:
	"Текущий рабочий каталог: \"%1\"",
Logs:
	"Логи",
FileUpload:
	"Загрузка файла",
DirEmpty:
	"не найдены файлы в \"%1\"",

// right click menu item directory
// directory
CreateSubDir:
	"создать подкаталог",
DeleteDir:
	"удалить",
RenameDir:
	"переименовать",

//file
DeleteFile:
	"удалить",
RenameFile:
	"переименовать",
RotateClockwise:
	"повернуть вправо",
RotateAntiClockwise:
	"повернуть влево",
ResizeImage:
	"изменить размер картинки",
ChangeCaption:
	"изменить подпись",

// create a file
WhatFilenameToCreateAs:
	"Файл будет сохранен, как?",
AskIfOverwrite:
	"Файл \"%1\" существует. Перезаписать?",
NoForwardslash:
	"\nНельзя использовать '/' в имени файла",

// messages management
CreateDirMessage:
	"Создать подкаталог в \"%1\":",
DelDirMessage:
	"Вы уверены, что хотите удалить каталог \"%1\"?",
DelFileMessage:
	"Вы уверены, что хотите удалить файл \"%1\"",
DelMultipleFilesMessage:
	"Вы уверены, что хотите удалить перечисленные файлы?\n\n'",
DownloadFileFromMessage:
	"Откуда закачивать файл?",
FileSavedAsMessage:
	"Как будет сохранен файл?",

//resize file
CurrentSize:
	"Текущий размер: \"%1\" x \"%2\"\n",
NewWidth:
	"Новая ширина?",
NewWidthConfirmTxt:
	"Новая ширина: \"%1\"\n",
NewHeight:
	"Новая высота?",
NewHeightConfirmTxt:
	"Новая высота: \"%1\"\n\nIs this correct?",

// log messages
RenamedFile:
	"переименование файла \"%1\" в \"%2\".",
DirRefreshed:
	"каталоги обновлены.",
FilesRefreshed:
	"файлы обновлены.",
NotMoreThanOneFile:
	"ошибка: нельзя выбрать больше одного файла за раз",
UnknownPanelState:
	"ошибка: неизвестное состояние панели.",
//MissingDirWrapper:
//	"error: missing directory wrapper: \"kfm_directories%1\".",
SetStylesError:
	"ошибка: нельзя установить \"%1\" в \"%2\.",
NoPanel:
	"ошибка: панель \"%1\" не существует.",
FileSelected:
	"выбран файл: \"%1\"",
Log_ChangeCaption:
	"изменение подписи с \"%1\" на \"%2\"",
UrlNotValidLog:
	"Ошибка: URL должен начинаться с \"http:\"",
MovingFilesTo:
	"перемещение файла [\"%1\"] в \"%2\"",

// error messages
DirectoryNameExists:
	"каталог с таким именем уже существует.",
FileNameNotAllowd:
	"ошибка: имя файла запрещено",
CouldNotWriteFile:
	"ошибка: нельзя записать файл \"%1\".",
CouldNotRemoveDir:
	"нельзя удалить каталог.\nвозможно он не пуст",
UrlNotValid:
	"ошибка: URL должен начинаться с \"http:\"",
CouldNotDownloadFile:
	"ошибка: невозможно загрузить файл \"%1\".",
FileTooLargeForThumb:
	"ошибка: \"%1\" слишком большой чтобы сделать эскиз. Замените файл на меньший по размеру",
CouldntReadDir:
	"ошибка: невозможно прочитать каталог",
CannotRenameFile:
	"ошибка: нельзя переименовать \"%1\" в \"%2\"",
FilenameAlreadyExists:
	"ошибка: файл с таким именем уже существует",

// new in 0.5
EditTextFile:
	"редактирование текстового файла",
CloseWithoutSavingQuestion:
	"Уверены, что хотите закрыть без сохранения?",
CloseWithoutSaving:
	"Закрыть без сохранения",
SaveThenClose:
	"Сохранять, при закрытии",
SaveThenCloseQuestion:
	"Сохранить изменения?",

// new in 0.6
LockPanels:
	"закрепить панели",
UnlockPanels:
	"снять закрепление панелей",
CreateEmptyFile:
	"создать пустой файл",
DownloadFileFromUrl:
	"загрузить с URL",
DirectoryProperties:
	"Свойства каталога",
SelectAll:
	"выделить все",
SelectNone:
	"снять выделение",
InvertSelection:
	"инвертировать выделение",
LoadingKFM:
	"загрузка KFM",
Name:
	"имя",
FileDetails:
	"Информация о файле",
Search:
	"Поиск",
IllegalDirectoryName:
	"некорректное имя папки \"%1\"",
RecursiveDeleteWarning:
	"\"%1\" не пустая\nВы уверены, что хотите стереть папку и все содержимое?\n*ВНИМАНИЕ* ПРОЦЕСС НЕОБРАТИМ!",
RmdirFailed:
	"невозможно удалить папку \"%1\"",
DirNotInDb:
	"папка отсутствует в базе данных",
ShowPanel:
	"показать панель \"%1\"",
ChangeCaption:
	"Изменить Подпись",
NewDirectory:
	"Новая папка",
Upload:
	"Загрузить",
NewCaptionIsThisCorrect:
	"Новая Подпись:\n%1\n\nЭто верно?",
Close:
	"закрыть",
Loading:
	"загружается",
AreYouSureYouWantToCloseKFM:
	"Вы уверены что хотие закрыть окно с KFM?",
PleaseSelectFileBeforeRename:
	"Пожалуйста, выберите файл, перед тем как его переименовать",
RenameOnlyOneFile:
	"Вы можете переименовать только один файл за одну операцию",
RenameFileToWhat:
	"Переименовать файл \"%1\" в ...?",
NoRestrictions:
	"нет ограничений",
Filename:
	"имя файла",
Maximise:
	"увеличить",
Minimise:
	"уменьшить",
AllowedFileExtensions:
	"допустимые расширения файла",
Filesize:
	"размер файла",
MoveDown:
	"опустить вниз",
Mimetype:
	"тип mime",
MoveUp:
	"поднять наверх",
Restore:
	"вернуть назад",
Caption:
	"подпись",
CopyFromURL:
	"Скопировать из URL",
ExtractZippedFile:
	"Распаковать файл ZIP",



// new in 0.8
ViewImage:
	"просмотреть изображение",
ReturnThumbnailToOpener:
	"использовать для показа эскиз вместо рисунка",
AddTagsToFiles:
	"добавить тэги к файлу(файлам)",
RemoveTagsFromFiles:
	"убрать тэги с файла(файлов)",
HowWouldYouLikeToRenameTheseFiles:
	"Как бы вы хотели переименовать эти файлы?\n\nнапример: \"images-***.jpg\" переименует файлы в \"images-001.jpg\", \"images-002.jpg\", ...",
YouMustPlaceTheWildcard:
	"Вы должны поместить групповой символ * где-то в шаблоне имени файла",
YouNeedMoreThan:
	"Вам надо более чем %1 * знаков чтобы создать %2 имен файлов",
NoFilesSelected:
	"не выбрано ни одного файла",
Tags:
	"тэги",
IfYouUseMultipleWildcards:
	"Если вы используете несколько групповых символов в шаблоне имени файла, они должны быть сгруппированы вместе",
NewCaption:
	"Новая подпись",
WhatMaximumSize:
	"Какой маскимальный размер должен быть возвращен?",
CommaSeparated:
	"разделенные запятой",
WhatIsTheNewTag:
	"Какой новый тэг?\nПри написании нескольких тэгов, разделяйте их запятой.",
WhichTagsDoYouWantToRemove:
	"Какие тэги вы хотите убрать?\nПри написании нескольких тэгов, разделяйте их запятой."

,
// New in 0.9
AllFiles: "все файлы",
AndNMore: "...и %1 еще...",
Browse: "Обзор...",
ExtractAfterUpload: "распаковать после загрузки",
NotAnImageOrImageDimensionsNotReported: "ошибка: не изображение, или размеры изображения не заданы",
PermissionDeniedCannotDeleteFile: "доступ запрещен: невозможно удалить файл",
RenameTheDirectoryToWhat: "Переименовать каталог '%1' в...?",
RenamedDirectoryAs: "Переименован '%1' в '%2'",
TheFilenameShouldEndWithN: " Имя файла должно заканчиваться на %1",
WhatFilenameDoYouWantToUse: "Какое имя файла вы хотите использовать?"

,
// New in 1.0
ZipUpFiles: "заархивировать файлы",
Cancel: "отменить"
	, // new in 1.2
	Icons                   : "иконки", // used to select mode of file view
	ListView                : "список", // used to select mode of file view
	SendToCms               : "послать в CMS", // close KFM and return the selected files to the CMS
	CannotMoveDirectory     : "доступ запрещен: невозможно переместить папку",
	LastModified            : "последний раз изменен", // part of File Details
	ImageDimensions         : "размеры изображения", // part of File Details
	CouldNotMoveFiles       : "ошибка: невозможно переместить файл[ы]",
	CopyFiles               : "скопировать файлы", // when dragging files to a directory, two choices appear - "copy files" and "move files"
	MoveFiles               : "переместить файлы",
	AboutKfm                : "о KFM",
	Errors                  : "Ошибки",
	Ok                      : "OK" // as in "OK / Cancel"
}

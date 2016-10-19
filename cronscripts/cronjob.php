<? try
{
shell_exec("mkdir hi");
}
catch(Exception $e)
{
echo "a" . $e->getMessage() . "B";}
?>

namespace App\Tenancy\Database;

use Stancl\Tenancy\Database\Connection as TenancyConnection;

class Connection extends TenancyConnection
{
    public function getTablePrefix()
    {
        $prefix = parent::getTablePrefix();
        $suffix = config('tenancy.tenants.' . $this->tenant->domain . '.suffix');

        if ($suffix === '{domain}') {
            $suffix = $this->tenant->domain;
        }

        return $prefix . '_' . $suffix;
    }
}